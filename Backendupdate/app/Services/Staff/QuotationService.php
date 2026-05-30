<?php

namespace App\Services\Staff;

use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\Quotation;
use App\Models\User;
use App\Support\QuotationStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class QuotationService
{
    public function paginate(Request $request, ?int $assignedTo = null): LengthAwarePaginator
    {
        return Quotation::query()
            ->with(['student', 'assignee', 'school'])
            ->when($assignedTo, fn ($q) => $q->where('assigned_to', $assignedTo))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->query('counsellor_id'), fn ($q, $id) => $q->where('assigned_to', $id))
            ->when(trim((string) $request->query('search', '')) !== '', function ($q) use ($request) {
                $search = trim((string) $request->query('search'));
                $q->where(function ($inner) use ($search) {
                    $inner->where('reference_no', 'like', "%{$search}%")
                        ->orWhereHas('student', fn ($s) => $s->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
                });
            })
            ->latest('id')
            ->paginate(25)
            ->withQueryString();
    }

    public function countOpen(?int $assignedTo = null): int
    {
        return Quotation::query()
            ->when($assignedTo, fn ($q) => $q->where('assigned_to', $assignedTo))
            ->whereIn('status', [QuotationStatus::DRAFT, QuotationStatus::SENT, QuotationStatus::ACCEPTED])
            ->count();
    }

    public function create(User $creator, array $data): Quotation
    {
        return Quotation::create([
            'reference_no' => $this->generateReference(),
            'status' => QuotationStatus::DRAFT,
            'booking_type' => $data['booking_type'],
            'student_user_id' => $data['student_user_id'],
            'created_by' => $creator->id,
            'assigned_to' => $data['assigned_to'] ?? $creator->id,
            'language_school_id' => $data['language_school_id'] ?? null,
            'course_id' => $data['course_id'] ?? null,
            'selection_snapshot' => $data['selection_snapshot'] ?? [],
            'pricing_snapshot' => $data['pricing_snapshot'] ?? [],
            'currency_snapshot' => $data['currency_snapshot'] ?? [],
            'total_amount' => $data['total_amount'] ?? 0,
            'display_currency' => strtoupper($data['display_currency'] ?? 'SAR'),
            'valid_until' => $data['valid_until'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function updateDraft(Quotation $quotation, array $data): Quotation
    {
        if ($quotation->status !== QuotationStatus::DRAFT) {
            throw ValidationException::withMessages([
                'status' => 'Only draft quotations can be edited.',
            ]);
        }

        $quotation->update([
            'booking_type' => $data['booking_type'] ?? $quotation->booking_type,
            'student_user_id' => $data['student_user_id'] ?? $quotation->student_user_id,
            'language_school_id' => $data['language_school_id'] ?? $quotation->language_school_id,
            'course_id' => $data['course_id'] ?? $quotation->course_id,
            'selection_snapshot' => $data['selection_snapshot'] ?? $quotation->selection_snapshot,
            'pricing_snapshot' => $data['pricing_snapshot'] ?? $quotation->pricing_snapshot,
            'currency_snapshot' => $data['currency_snapshot'] ?? $quotation->currency_snapshot,
            'total_amount' => $data['total_amount'] ?? $quotation->total_amount,
            'display_currency' => $data['display_currency'] ?? $quotation->display_currency,
            'valid_until' => $data['valid_until'] ?? $quotation->valid_until,
            'notes' => $data['notes'] ?? $quotation->notes,
        ]);

        return $quotation->fresh();
    }

    public function markSent(Quotation $quotation, ?string $validUntil = null): Quotation
    {
        $quotation->update([
            'status' => QuotationStatus::SENT,
            'valid_until' => $validUntil ?? $quotation->valid_until ?? now()->addDays(14)->toDateString(),
        ]);

        return $quotation->fresh();
    }

    public function convertToBooking(Quotation $quotation, User $actor): LanguageCourseBooking|OnlineCourseBooking
    {
        if (!in_array($quotation->status, [QuotationStatus::SENT, QuotationStatus::ACCEPTED, QuotationStatus::DRAFT], true)) {
            throw ValidationException::withMessages([
                'status' => 'This quotation cannot be converted.',
            ]);
        }

        if ($quotation->converted_booking_id) {
            throw ValidationException::withMessages([
                'status' => 'This quotation was already converted.',
            ]);
        }

        return DB::transaction(function () use ($quotation, $actor) {
            $student = $quotation->student;
            $selection = is_array($quotation->selection_snapshot) ? $quotation->selection_snapshot : [];
            $pricing = is_array($quotation->pricing_snapshot) ? $quotation->pricing_snapshot : [];

            if ($quotation->booking_type === 'online_course') {
                $booking = OnlineCourseBooking::create([
                    'reference_no' => $this->generateBookingReference('OC'),
                    'user_id' => $student->id,
                    'language_school_id' => $quotation->language_school_id,
                    'online_course_id' => $quotation->course_id,
                    'status' => 'pending',
                    'source' => 'staff_quotation',
                    'assigned_to' => $quotation->assigned_to ?? $actor->id,
                    'contact_name' => $student->name,
                    'contact_email' => $student->email,
                    'contact_phone' => $student->phone,
                    'weeks' => $selection['weeks'] ?? null,
                    'start_date' => $selection['start_date'] ?? null,
                    'course_fee' => $pricing['course_fee'] ?? $quotation->total_amount,
                    'registration_fee' => $pricing['registration_fee'] ?? 0,
                    'subtotal' => $quotation->total_amount,
                    'total_amount' => $quotation->total_amount,
                    'display_currency' => $quotation->display_currency,
                    'currency_snapshot' => $quotation->currency_snapshot,
                    'pricing_snapshot' => $quotation->pricing_snapshot,
                    'selection_snapshot' => $quotation->selection_snapshot,
                    'notes' => $quotation->notes,
                ]);

                $quotation->update([
                    'status' => QuotationStatus::CONVERTED,
                    'converted_booking_type' => 'online_course',
                    'converted_booking_id' => $booking->id,
                ]);

                return $booking;
            }

            $booking = LanguageCourseBooking::create([
                'reference_no' => $this->generateBookingReference('LC'),
                'user_id' => $student->id,
                'language_school_id' => $quotation->language_school_id,
                'course_id' => $quotation->course_id,
                'status' => 'pending',
                'source' => 'staff_quotation',
                'assigned_to' => $quotation->assigned_to ?? $actor->id,
                'contact_name' => $student->name,
                'contact_email' => $student->email,
                'contact_phone' => $student->phone,
                'weeks' => $selection['weeks'] ?? 1,
                'start_date' => $selection['start_date'] ?? now()->toDateString(),
                'selection_snapshot' => $quotation->selection_snapshot,
                'pricing_snapshot' => $quotation->pricing_snapshot,
                'currency_snapshot' => $quotation->currency_snapshot,
                'subtotal' => $quotation->total_amount,
                'total_amount' => $quotation->total_amount,
                'display_currency' => $quotation->display_currency,
                'notes' => $quotation->notes,
            ]);

            $quotation->update([
                'status' => QuotationStatus::CONVERTED,
                'converted_booking_type' => 'language_course',
                'converted_booking_id' => $booking->id,
            ]);

            return $booking;
        });
    }

    private function generateReference(): string
    {
        do {
            $ref = 'QUO-' . strtoupper(Str::random(8));
        } while (Quotation::query()->where('reference_no', $ref)->exists());

        return $ref;
    }

    private function generateBookingReference(string $prefix): string
    {
        return $prefix . '-' . strtoupper(Str::random(8));
    }
}
