<?php

namespace App\Services\Staff;

use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\Quotation;
use App\Models\User;

class StaffAssignmentService
{
    public function assignBooking(LanguageCourseBooking|OnlineCourseBooking $booking, ?int $counsellorId): LanguageCourseBooking|OnlineCourseBooking
    {
        $booking->update(['assigned_to' => $counsellorId]);

        return $booking->fresh();
    }

    public function assignQuotation(Quotation $quotation, ?int $counsellorId): Quotation
    {
        $quotation->update(['assigned_to' => $counsellorId]);

        return $quotation->fresh();
    }

    public function counsellorWorkload(): array
    {
        $counsellors = User::query()
            ->where('role', 'counsellor')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        return $counsellors->map(function (User $user) {
            $language = LanguageCourseBooking::query()
                ->where('assigned_to', $user->id)
                ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
                ->count();

            $online = OnlineCourseBooking::query()
                ->where('assigned_to', $user->id)
                ->whereNotIn('status', ['completed', 'cancelled', 'rejected'])
                ->count();

            return [
                'id' => $user->id,
                'name' => $user->name,
                'open_bookings' => $language + $online,
            ];
        })->all();
    }
}
