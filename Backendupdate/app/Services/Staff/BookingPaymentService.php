<?php

namespace App\Services\Staff;

use App\Models\BookingPaymentEvent;
use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\User;
use App\Support\BookingStatus;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BookingPaymentService
{
    /** @return list<string> */
    public function allowedTransitions(string $currentStatus, bool $isTeam): array
    {
        $map = [
            BookingStatus::PENDING => [BookingStatus::CONFIRMED, BookingStatus::CANCELLED, BookingStatus::REJECTED],
            BookingStatus::CONFIRMED => [BookingStatus::PAID, BookingStatus::CANCELLED, BookingStatus::REJECTED],
            BookingStatus::PAID => [BookingStatus::COMPLETED],
            BookingStatus::COMPLETED => [],
            BookingStatus::CANCELLED => [],
            BookingStatus::REJECTED => [],
        ];

        $allowed = $map[$currentStatus] ?? [];

        if ($isTeam) {
            $allowed = array_values(array_unique(array_merge($allowed, BookingStatus::ALL)));
        }

        return $allowed;
    }

    public function updateStatus(
        LanguageCourseBooking|OnlineCourseBooking $booking,
        string $type,
        User $actor,
        string $newStatus,
        array $paymentData = [],
        bool $isTeam = false,
    ): LanguageCourseBooking|OnlineCourseBooking {
        $current = $booking->status;

        if (!in_array($newStatus, $this->allowedTransitions($current, $isTeam), true) && $newStatus !== $current) {
            throw ValidationException::withMessages([
                'status' => "Cannot change status from {$current} to {$newStatus}.",
            ]);
        }

        $updates = ['status' => $newStatus];

        if ($newStatus === BookingStatus::PAID) {
            $updates['paid_at'] = now();
            $updates['payment_method'] = $paymentData['payment_method'] ?? $booking->payment_method ?? 'bank_transfer';
            $updates['payment_reference'] = $paymentData['payment_reference'] ?? $booking->payment_reference;
            $updates['payment_notes'] = $paymentData['payment_notes'] ?? $booking->payment_notes;
        }

        if (!empty($paymentData['payment_reference'])) {
            $updates['payment_reference'] = $paymentData['payment_reference'];
        }

        if (!empty($paymentData['payment_notes'])) {
            $updates['payment_notes'] = $paymentData['payment_notes'];
        }

        if (!empty($paymentData['payment_method'])) {
            $updates['payment_method'] = $paymentData['payment_method'];
        }

        if ($paymentData['payment_proof'] ?? null instanceof UploadedFile) {
            $updates['payment_proof_path'] = $this->storeProof($paymentData['payment_proof'], $booking, $type);
        }

        if ($isTeam && ($paymentData['verify_payment'] ?? false) && $newStatus === BookingStatus::COMPLETED) {
            $updates['payment_verified_by'] = $actor->id;
            $updates['payment_verified_at'] = now();
        }

        if (!empty($paymentData['notes'])) {
            $updates['notes'] = $paymentData['notes'];
        }

        $booking->update($updates);

        if ($newStatus !== $current) {
            BookingPaymentEvent::create([
                'booking_type' => $type,
                'booking_id' => $booking->id,
                'user_id' => $actor->id,
                'old_status' => $current,
                'new_status' => $newStatus,
                'note' => $paymentData['note'] ?? null,
                'meta' => [
                    'payment_reference' => $updates['payment_reference'] ?? null,
                ],
            ]);
        }

        return $booking->fresh();
    }

    public function recentEvents(?int $assignedTo = null, int $limit = 10)
    {
        return BookingPaymentEvent::query()
            ->with('user')
            ->when($assignedTo, function ($query) use ($assignedTo) {
                $query->where(function ($q) use ($assignedTo) {
                    $q->where(function ($inner) use ($assignedTo) {
                        $inner->where('booking_type', 'language_course')
                            ->whereIn('booking_id', function ($sub) use ($assignedTo) {
                                $sub->select('id')
                                    ->from('language_course_bookings')
                                    ->where('assigned_to', $assignedTo);
                            });
                    })->orWhere(function ($inner) use ($assignedTo) {
                        $inner->where('booking_type', 'online_course')
                            ->whereIn('booking_id', function ($sub) use ($assignedTo) {
                                $sub->select('id')
                                    ->from('online_course_bookings')
                                    ->where('assigned_to', $assignedTo);
                            });
                    });
                });
            })
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    private function storeProof(UploadedFile $file, LanguageCourseBooking|OnlineCourseBooking $booking, string $type): string
    {
        $dir = "booking-payments/{$type}/{$booking->id}";

        return $file->store($dir, 'public');
    }
}
