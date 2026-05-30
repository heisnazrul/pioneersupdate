<?php

namespace App\Http\Controllers\Counsellor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Staff\Concerns\InteractsWithStaffPanel;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageOnlineCourse;
use App\Models\Quotation;
use App\Models\User;
use App\Services\Staff\BookingPdfService;
use App\Services\Staff\QuotationService;
use App\Support\QuotationStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class QuotationController extends Controller
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

    public function index(Request $request, QuotationService $service): View
    {
        return view('staff.quotations.index', [
            'quotations' => $service->paginate($request, $this->assignedScope()),
            'statuses' => QuotationStatus::ALL,
            'activeStatus' => $request->query('status'),
            'search' => $request->query('search', ''),
            'routePrefix' => $this->panelRoutePrefix(),
            'showCounsellorFilter' => false,
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        return redirect()->route('counsellor.bookings.create', ['mode' => 'quotation']);
    }

    public function store(Request $request, QuotationService $service): RedirectResponse
    {
        return redirect()->route('counsellor.bookings.create', ['mode' => 'quotation']);
    }

    public function show(Quotation $quotation): View
    {
        $this->ensureQuotationAccess($quotation);
        $quotation->load(['student', 'school', 'assignee']);

        return view('staff.quotations.show', [
            'quotation' => $quotation,
            'statuses' => QuotationStatus::ALL,
            'routePrefix' => $this->panelRoutePrefix(),
            'showAssign' => false,
            'counsellors' => collect(),
        ]);
    }

    public function edit(Quotation $quotation): View
    {
        $this->ensureQuotationAccess($quotation);
        abort_unless($quotation->status === QuotationStatus::DRAFT, 403);

        return view('staff.quotations.edit', [
            'quotation' => $quotation,
            'students' => User::query()->where('role', 'lg_student')->orderBy('name')->get(['id', 'name', 'email']),
            'schools' => LanguageSchool::query()->orderBy('name_en')->get(['id', 'name_en']),
            'routePrefix' => $this->panelRoutePrefix(),
        ]);
    }

    public function update(Request $request, Quotation $quotation, QuotationService $service): RedirectResponse
    {
        $this->ensureQuotationAccess($quotation);
        $service->updateDraft($quotation, $this->validatedQuotation($request));

        return redirect()
            ->route($this->panelRoutePrefix() . '.quotations.show', $quotation)
            ->with('success', 'Quotation updated.');
    }

    public function send(Request $request, Quotation $quotation, QuotationService $service): RedirectResponse
    {
        $this->ensureQuotationAccess($quotation);
        $data = $request->validate([
            'valid_until' => ['nullable', 'date'],
        ]);
        $service->markSent($quotation, $data['valid_until'] ?? null);

        return back()->with('success', 'Quotation marked as sent.');
    }

    public function convert(Request $request, Quotation $quotation, QuotationService $service): RedirectResponse
    {
        $this->ensureQuotationAccess($quotation);
        $booking = $service->convertToBooking($quotation, $request->user());
        $type = $quotation->converted_booking_type;

        return redirect()
            ->route($this->panelRoutePrefix() . '.bookings.show', ['type' => $type, 'id' => $booking->id])
            ->with('success', 'Quotation converted to booking.');
    }

    public function pdf(Quotation $quotation, BookingPdfService $pdf): Response
    {
        $this->ensureQuotationAccess($quotation);

        return $pdf->quotationProforma($quotation);
    }

    private function ensureQuotationAccess(Quotation $quotation): void
    {
        $scope = $this->assignedScope();
        if ($scope !== null && (int) $quotation->assigned_to !== $scope && (int) $quotation->created_by !== $scope) {
            abort(403);
        }
    }

    /** @return array<string, mixed> */
    private function validatedQuotation(Request $request): array
    {
        $data = $request->validate([
            'student_user_id' => ['required', 'exists:users,id'],
            'booking_type' => ['required', Rule::in(['language_course', 'online_course'])],
            'language_school_id' => ['nullable', 'exists:language_schools,id'],
            'course_id' => ['nullable', 'integer'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'display_currency' => ['nullable', 'string', 'max:8'],
            'valid_until' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
            'weeks' => ['nullable', 'integer', 'min:1'],
            'start_date' => ['nullable', 'date'],
        ]);

        $courseName = null;
        if ($data['booking_type'] === 'language_course' && !empty($data['course_id'])) {
            $course = LanguageSchoolCourse::query()->find($data['course_id']);
            $courseName = $course?->name_en ?? $course?->name;
        } elseif ($data['booking_type'] === 'online_course' && !empty($data['course_id'])) {
            $course = LanguageOnlineCourse::query()->find($data['course_id']);
            $courseName = $course?->name_en ?? $course?->name;
        }

        $data['selection_snapshot'] = [
            'weeks' => $data['weeks'] ?? null,
            'start_date' => $data['start_date'] ?? null,
            'course' => [
                'id' => $data['course_id'] ?? null,
                'name' => $courseName,
            ],
        ];
        $data['pricing_snapshot'] = [
            'total' => $data['total_amount'],
            'course_fee' => $data['total_amount'],
        ];
        unset($data['weeks'], $data['start_date']);

        return $data;
    }
}
