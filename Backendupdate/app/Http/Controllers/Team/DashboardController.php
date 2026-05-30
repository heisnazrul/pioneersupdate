<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Staff\Concerns\InteractsWithStaffPanel;
use App\Services\Staff\BookingPaymentService;
use App\Services\Staff\BookingQueryService;
use App\Services\Staff\QuotationService;
use App\Services\Staff\StaffAssignmentService;
use App\Services\Staff\StaffStudentService;
use App\Support\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use InteractsWithStaffPanel;

    protected function panelRoutePrefix(): string
    {
        return 'team';
    }

    protected function assignedScope(): ?int
    {
        return null;
    }

    protected function isTeamPanel(): bool
    {
        return true;
    }

    public function __invoke(
        Request $request,
        BookingQueryService $bookings,
        BookingPaymentService $payments,
        StaffStudentService $students,
        QuotationService $quotations,
        StaffAssignmentService $assignments,
    ): View {
        return view('team.dashboard', [
            'statusCounts' => $bookings->countsByStatus(null),
            'pendingPayments' => $bookings->pendingPaymentCount(null),
            'studentCount' => $students->countForStaff(null),
            'openQuotations' => $quotations->countOpen(null),
            'recentEvents' => $payments->recentEvents(null, 15),
            'workload' => $assignments->counsellorWorkload(),
            'statuses' => BookingStatus::ALL,
        ]);
    }
}
