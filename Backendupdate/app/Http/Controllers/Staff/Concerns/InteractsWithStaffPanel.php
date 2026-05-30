<?php

namespace App\Http\Controllers\Staff\Concerns;

use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\User;
use App\Services\Staff\BookingQueryService;
use Illuminate\Http\Response;

trait InteractsWithStaffPanel
{
    abstract protected function panelRoutePrefix(): string;

    abstract protected function assignedScope(): ?int;

    abstract protected function isTeamPanel(): bool;

    protected function ensureBookingAccess(LanguageCourseBooking|OnlineCourseBooking $booking): void
    {
        $scope = $this->assignedScope();

        if ($scope !== null && (int) $booking->assigned_to !== $scope) {
            abort(Response::HTTP_FORBIDDEN);
        }
    }

    protected function counsellors()
    {
        return User::query()
            ->where('role', 'counsellor')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);
    }

    protected function bookingQuery(): BookingQueryService
    {
        return app(BookingQueryService::class);
    }
}
