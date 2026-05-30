<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Counsellor\QuotationController as CounsellorQuotationController;
use App\Models\Quotation;
use App\Models\User;
use App\Services\Staff\BookingPdfService;
use App\Services\Staff\QuotationService;
use App\Services\Staff\StaffAssignmentService;
use App\Support\QuotationStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class QuotationController extends CounsellorQuotationController
{
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

    public function index(Request $request, QuotationService $service): View
    {
        return view('staff.quotations.index', [
            'quotations' => $service->paginate($request, null),
            'statuses' => QuotationStatus::ALL,
            'activeStatus' => $request->query('status'),
            'search' => $request->query('search', ''),
            'routePrefix' => $this->panelRoutePrefix(),
            'showCounsellorFilter' => true,
            'counsellors' => $this->counsellors(),
            'activeCounsellor' => $request->query('counsellor_id'),
        ]);
    }

    public function create(Request $request): View
    {
        return view('staff.quotations.create', [
            'students' => User::query()->where('role', 'lg_student')->orderBy('name')->get(['id', 'name', 'email']),
            'schools' => \App\Models\LanguageSchool::query()->orderBy('name_en')->get(['id', 'name_en']),
            'routePrefix' => $this->panelRoutePrefix(),
        ]);
    }

    public function show(Quotation $quotation): View
    {
        $quotation->load(['student', 'school', 'assignee']);

        return view('staff.quotations.show', [
            'quotation' => $quotation,
            'statuses' => QuotationStatus::ALL,
            'routePrefix' => $this->panelRoutePrefix(),
            'showAssign' => true,
            'counsellors' => $this->counsellors(),
        ]);
    }

    public function assign(Request $request, Quotation $quotation, StaffAssignmentService $assignments): RedirectResponse
    {
        $data = $request->validate([
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $assignments->assignQuotation($quotation, $data['assigned_to'] ?? null);

        return back()->with('success', 'Quotation reassigned.');
    }

    public function pdf(Quotation $quotation, BookingPdfService $pdf): Response
    {
        return $pdf->quotationProforma($quotation);
    }
}
