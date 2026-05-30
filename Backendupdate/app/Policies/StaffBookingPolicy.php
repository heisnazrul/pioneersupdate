<?php

namespace App\Policies;

use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\Quotation;
use App\Models\User;

class StaffBookingPolicy
{
    public function view(User $user, LanguageCourseBooking|OnlineCourseBooking $booking): bool
    {
        if (in_array($user->role, ['admin', 'team'], true)) {
            return true;
        }

        if ($user->role === 'counsellor') {
            return (int) $booking->assigned_to === $user->id;
        }

        return false;
    }

    public function update(User $user, LanguageCourseBooking|OnlineCourseBooking $booking): bool
    {
        return $this->view($user, $booking);
    }
}
