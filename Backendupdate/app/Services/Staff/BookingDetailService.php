<?php

namespace App\Services\Staff;

use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Support\LanguageCourseBookingPresenter;
use App\Support\OnlineCourseBookingPresenter;

class BookingDetailService
{
    public function __construct(
        private readonly LanguageCourseBookingPresenter $languagePresenter,
        private readonly OnlineCourseBookingPresenter $onlinePresenter,
    ) {
    }

    public function payload(LanguageCourseBooking|OnlineCourseBooking $booking, string $type, bool $schoolCopy = false): ?array
    {
        if ($booking instanceof OnlineCourseBooking) {
            return $this->onlinePresenter->detail($booking);
        }

        if ($schoolCopy) {
            return $this->languagePresenter->schoolDetail($booking);
        }

        return $this->languagePresenter->detail($booking);
    }
}
