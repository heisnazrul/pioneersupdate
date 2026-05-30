<?php

namespace App\Http\Controllers\Counsellor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Staff\Concerns\InteractsWithStaffPanel;
use App\Models\LanguageOnlineCourse;
use App\Models\LanguageSchoolBranch;
use App\Models\OnlineCourseBooking;
use App\Models\Quotation;
use App\Models\User;
use App\Services\LanguageCourse\LanguageCourseBookingService;
use App\Services\Staff\QuotationService;
use App\Services\Staff\StaffBookingCatalogService;
use App\Services\Staff\StaffBookingWizardSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BookingWizardController extends Controller
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

    public function start(Request $request, StaffBookingWizardSession $wizard): RedirectResponse
    {
        $wizard->reset($request->query('mode', 'booking'));

        return redirect()->route('counsellor.bookings.wizard.step', ['step' => 1]);
    }

    public function showStep(int $step, Request $request, StaffBookingWizardSession $wizard, StaffBookingCatalogService $catalog): View|RedirectResponse
    {
        $data = $wizard->all();
        $stepName = $wizard->stepName($step, $data);

        if (! $stepName) {
            return redirect()->route('counsellor.bookings.wizard.step', ['step' => 1]);
        }

        if ($step > 1 && empty($data['booking_type'])) {
            return redirect()->route('counsellor.bookings.wizard.step', ['step' => 1]);
        }

        if ($stepName !== 'type') {
            if (empty($data['school_id']) && ! in_array($stepName, ['school'], true)) {
                return redirect()->route('counsellor.bookings.wizard.step', ['step' => $wizard->stepNumber('school', $data)]);
            }
            if ($data['booking_type'] === 'language_course' && empty($data['branch_id']) && ! in_array($stepName, ['school', 'branch'], true)) {
                return redirect()->route('counsellor.bookings.wizard.step', ['step' => $wizard->stepNumber('branch', $data)]);
            }
            if (empty($data['course_id']) && ! in_array($stepName, ['school', 'branch', 'course'], true)) {
                return redirect()->route('counsellor.bookings.wizard.step', ['step' => $wizard->stepNumber('course', $data)]);
            }
            if (empty($data['student_user_id']) && ! in_array($stepName, ['school', 'branch', 'course', 'student'], true)) {
                return redirect()->route('counsellor.bookings.wizard.step', ['step' => $wizard->stepNumber('student', $data)]);
            }
        }

        $viewData = [
            'step' => $step,
            'stepName' => $stepName,
            'totalSteps' => $wizard->totalSteps($data),
            'stepLabels' => $wizard->stepLabels($data),
            'wizard' => $data,
            'categories' => $catalog->categories(),
            'students' => $catalog->studentsForCounsellor((int) auth()->id()),
        ];

        return match ($stepName) {
            'type' => view('counsellor.bookings.wizard.steps.type', $viewData),
            'school' => view('counsellor.bookings.wizard.steps.school', array_merge($viewData, [
                'schools' => $catalog->schools(
                    $data['category_id'] ?: null,
                    $data['booking_type'],
                ),
            ])),
            'branch' => view('counsellor.bookings.wizard.steps.branch', array_merge($viewData, [
                'branches' => $catalog->branches(
                    (int) $data['school_id'],
                    $data['category_id'] ?: null,
                ),
            ])),
            'course' => $this->courseStepView($viewData, $data, $catalog),
            'student' => view('counsellor.bookings.wizard.steps.student', $viewData),
            'schedule' => view('counsellor.bookings.wizard.steps.schedule', $viewData),
            'extras' => $this->extrasStepView($viewData, $data, $catalog),
            'review' => $this->reviewStepView($viewData, $data, $catalog),
            default => redirect()->route('counsellor.bookings.wizard.step', ['step' => 1]),
        };
    }

    public function processStep(int $step, Request $request, StaffBookingWizardSession $wizard): RedirectResponse
    {
        $data = $wizard->all();
        $stepName = $wizard->stepName($step, $data);

        if (! $stepName) {
            return redirect()->route('counsellor.bookings.wizard.step', ['step' => 1]);
        }

        $this->validateStep($request, $stepName, $data);
        $wizard->mergeFromRequest($request, $stepName);

        if ($stepName === 'review') {
            return $this->finalize($request, $wizard);
        }

        return redirect()->route('counsellor.bookings.wizard.step', ['step' => $step + 1]);
    }

    public function back(int $step, StaffBookingWizardSession $wizard): RedirectResponse
    {
        $target = max(1, $step - 1);

        return redirect()->route('counsellor.bookings.wizard.step', ['step' => $target]);
    }

    private function validateStep(Request $request, string $stepName, array $data): void
    {
        match ($stepName) {
            'type' => $request->validate([
                'booking_type' => ['required', Rule::in(['language_course', 'online_course'])],
                'category_id' => ['nullable', 'integer'],
            ]),
            'school' => $request->validate(['school_id' => ['required', 'integer']]),
            'branch' => $request->validate(['branch_id' => ['required', 'integer']]),
            'course' => $request->validate(['course_id' => ['required', 'integer']]),
            'student' => $request->validate(['student_user_id' => ['required', 'exists:users,id']]),
            'schedule' => $request->validate([
                'weeks' => ['required', 'integer', 'min:1'],
                'start_date' => ['required', 'date'],
                'display_currency' => ['required', 'string', 'max:8'],
                'acc_age' => ['nullable', 'integer', 'min:1'],
            ]),
            'extras' => $request->validate([
                'accommodation_id' => ['nullable'],
                'pickup_id' => ['nullable'],
                'insurance_ids' => ['nullable', 'array'],
            ]),
            'review' => $request->validate([
                'action' => ['required', Rule::in(['booking', 'quotation'])],
                'notes' => ['nullable', 'string'],
            ]),
            default => null,
        };
    }

    private function courseStepView(array $viewData, array $data, StaffBookingCatalogService $catalog): View
    {
        if ($data['booking_type'] === 'online_course') {
            return view('counsellor.bookings.wizard.steps.course', array_merge($viewData, [
                'courses' => $catalog->onlineCourses((int) $data['school_id'], $data['category_id'] ?: null),
            ]));
        }

        return view('counsellor.bookings.wizard.steps.course', array_merge($viewData, [
            'courses' => $catalog->branchCatalog((int) $data['branch_id'], $data['category_id'] ?: null)['courses'] ?? collect(),
        ]));
    }

    private function extrasStepView(array $viewData, array $data, StaffBookingCatalogService $catalog): View
    {
        $catalogPayload = $catalog->branchCatalog((int) $data['branch_id'], $data['category_id'] ?: null);

        return view('counsellor.bookings.wizard.steps.extras', array_merge($viewData, [
            'catalog' => $catalogPayload,
        ]));
    }

    private function reviewStepView(array $viewData, array $data, StaffBookingCatalogService $catalog): View
    {
        $selection = $this->selectionFromWizard($data);
        $pricing = null;
        $catalogPayload = null;

        if ($data['booking_type'] === 'language_course') {
            $catalogPayload = $catalog->branchCatalog((int) $data['branch_id'], $data['category_id'] ?: null);
            $pricing = $catalog->previewLanguagePricing(
                $selection,
                $catalogPayload,
                $data['display_currency'],
            );
        } else {
            $course = LanguageOnlineCourse::query()->find($data['course_id']);
            if ($course) {
                $weeks = max(1, (int) $data['weeks']);
                $courseFee = (float) $course->fee_amount;
                if ($course->fee_type === 'weekly') {
                    $courseFee *= $weeks;
                }
                $registration = (float) ($course->registration_fee ?? 0);
                $pricing = [
                    'courseTotal' => round($courseFee, 2),
                    'total' => round($courseFee + $registration, 2),
                ];
            }
        }

        return view('counsellor.bookings.wizard.steps.review', array_merge($viewData, [
            'pricing' => $pricing,
            'selection' => $selection,
            'student' => User::find($data['student_user_id']),
        ]));
    }

    private function finalize(Request $request, StaffBookingWizardSession $wizard): RedirectResponse
    {
        $data = $wizard->all();
        $action = $request->input('action', 'booking');
        $student = User::query()->where('id', $data['student_user_id'])->where('role', 'lg_student')->firstOrFail();
        $staff = $request->user();
        $selection = $this->selectionFromWizard($data);
        $currency = strtoupper($data['display_currency']);

        if ($data['booking_type'] === 'online_course') {
            $result = $this->storeOnlineBooking($staff, $student, $selection, $currency, $data['notes'] ?? null);
            $wizard->reset();

            return redirect()
                ->route('counsellor.bookings.show', ['type' => 'online_course', 'id' => $result['booking_id']])
                ->with('success', 'Online booking created.');
        }

        $catalog = app(StaffBookingCatalogService::class);
        $catalogPayload = $catalog->branchCatalog((int) $data['branch_id'], $data['category_id'] ?: null);
        $pricing = $catalog->previewLanguagePricing($selection, $catalogPayload, $currency);

        if ($action === 'quotation') {
            $quotation = app(QuotationService::class)->create($staff, [
                'booking_type' => 'language_course',
                'student_user_id' => $student->id,
                'assigned_to' => $staff->id,
                'language_school_id' => LanguageSchoolBranch::find($data['branch_id'])?->school_id,
                'course_id' => $data['course_id'],
                'selection_snapshot' => $selection,
                'pricing_snapshot' => $pricing,
                'total_amount' => $pricing['total'] ?? 0,
                'display_currency' => $currency,
                'notes' => $data['notes'] ?? null,
            ]);
            app(QuotationService::class)->markSent($quotation);
            $wizard->reset();

            return redirect()
                ->route('counsellor.quotations.show', $quotation)
                ->with('success', 'Quotation created successfully.');
        }

        $result = app(LanguageCourseBookingService::class)->createForStaffStudent($student, $staff, [
            'selection' => $selection,
            'display_currency' => $currency,
            'notes' => $data['notes'] ?? null,
        ]);
        $wizard->reset();

        return redirect()
            ->route('counsellor.bookings.show', ['type' => 'language_course', 'id' => $result['booking_id']])
            ->with('success', 'Booking created successfully.');
    }

    /** @return array<string, mixed> */
    private function selectionFromWizard(array $data): array
    {
        return [
            'course_id' => (int) $data['course_id'],
            'weeks' => (int) $data['weeks'],
            'start_date' => $data['start_date'],
            'acc_age' => (int) ($data['acc_age'] ?? 18),
            'accommodation_id' => $data['accommodation_id'] ?: 'no-acc',
            'pickup_id' => $data['pickup_id'] ?: null,
            'insurance_ids' => $data['insurance_ids'] ?? [],
        ];
    }

    /** @return array<string, mixed> */
    private function storeOnlineBooking(User $staff, User $student, array $selection, string $currency, ?string $notes): array
    {
        return DB::transaction(function () use ($staff, $student, $selection, $currency, $notes) {
            $course = LanguageOnlineCourse::query()->findOrFail((int) $selection['course_id']);
            $weeks = max(1, (int) ($selection['weeks'] ?? 1));
            $courseFee = (float) $course->fee_amount;
            if ($course->fee_type === 'weekly') {
                $courseFee *= $weeks;
            }
            $registrationFee = (float) ($course->registration_fee ?? 0);
            $total = $courseFee + $registrationFee;

            $booking = OnlineCourseBooking::create([
                'reference_no' => 'OC-' . strtoupper(substr(uniqid(), -8)),
                'user_id' => $student->id,
                'language_school_id' => $course->language_school_id,
                'online_course_id' => $course->id,
                'status' => 'pending',
                'source' => 'staff_panel',
                'assigned_to' => $staff->id,
                'contact_name' => $student->name,
                'contact_email' => $student->email,
                'contact_phone' => $student->phone,
                'weeks' => $weeks,
                'start_date' => $selection['start_date'] ?? null,
                'course_fee' => $courseFee,
                'registration_fee' => $registrationFee,
                'subtotal' => $total,
                'total_amount' => $total,
                'display_currency' => $currency,
                'pricing_snapshot' => ['course_fee' => $courseFee, 'registration_fee' => $registrationFee, 'total' => $total],
                'selection_snapshot' => $selection,
                'notes' => $notes,
            ]);

            return ['booking_id' => $booking->id, 'reference_no' => $booking->reference_no];
        });
    }
}
