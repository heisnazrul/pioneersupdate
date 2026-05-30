<?php

namespace App\Http\Controllers\Counsellor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Staff\Concerns\InteractsWithStaffPanel;
use App\Models\Quotation;
use App\Models\User;
use App\Services\Staff\BookingPaymentService;
use App\Services\Staff\BookingQueryService;
use App\Services\Staff\QuotationService;
use App\Services\Staff\StaffStudentService;
use App\Support\BookingStatus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    use InteractsWithStaffPanel;

    protected function panelRoutePrefix(): string
    {
        return 'counsellor';
    }

    protected function assignedScope(): ?int
    {
        return auth()->id();
    }

    protected function isTeamPanel(): bool
    {
        return false;
    }

    public function __invoke(
        Request $request,
        BookingQueryService $bookings,
        BookingPaymentService $payments,
        StaffStudentService $students,
        QuotationService $quotations,
    ): View {
        $scope = $this->assignedScope();

        return view('counsellor.dashboard', [
            'statusCounts' => $bookings->countsByStatus($scope),
            'pendingPayments' => $bookings->pendingPaymentCount($scope),
            'studentCount' => $students->countForStaff($scope),
            'openQuotations' => $quotations->countOpen($scope),
            'recentEvents' => $payments->recentEvents($scope),
            'statuses' => BookingStatus::ALL,
        ]);
    }
}
