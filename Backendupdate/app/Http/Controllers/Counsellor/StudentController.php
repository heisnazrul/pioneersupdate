<?php

namespace App\Http\Controllers\Counsellor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Staff\Concerns\InteractsWithStaffPanel;
use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\Quotation;
use App\Models\User;
use App\Services\Staff\StaffStudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
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

    public function index(Request $request, StaffStudentService $service): View
    {
        return view('staff.students.index', [
            'students' => $service->paginate($request, $this->assignedScope()),
            'routePrefix' => $this->panelRoutePrefix(),
            'showAssign' => false,
            'counsellors' => collect(),
            'search' => $request->query('search', ''),
        ]);
    }

    public function create(): View
    {
        return view('staff.students.create', [
            'routePrefix' => $this->panelRoutePrefix(),
        ]);
    }

    public function store(Request $request, StaffStudentService $service): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:255'],
        ]);

        $student = $service->createStudent($request->user(), $data);

        return redirect()
            ->route($this->panelRoutePrefix() . '.students.show', $student)
            ->with('success', 'Student created.');
    }

    public function show(User $student, Request $request): View
    {
        abort_unless($student->role === 'lg_student', 404);

        $scope = $this->assignedScope();
        $assigned = $student->staffAssignments()
            ->where('staff_user_id', $scope)
            ->exists();

        $hasBooking = LanguageCourseBooking::query()
            ->where('user_id', $student->id)
            ->where('assigned_to', $scope)
            ->exists()
            || OnlineCourseBooking::query()
                ->where('user_id', $student->id)
                ->where('assigned_to', $scope)
                ->exists();

        abort_unless($assigned || $hasBooking, 403);

        $bookings = LanguageCourseBooking::query()
            ->where('user_id', $student->id)
            ->when($scope, fn ($q) => $q->where('assigned_to', $scope))
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn ($b) => ['type' => 'language_course', 'model' => $b]);

        $online = OnlineCourseBooking::query()
            ->where('user_id', $student->id)
            ->when($scope, fn ($q) => $q->where('assigned_to', $scope))
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn ($b) => ['type' => 'online_course', 'model' => $b]);

        $quotations = Quotation::query()
            ->where('student_user_id', $student->id)
            ->when($scope, fn ($q) => $q->where('assigned_to', $scope))
            ->latest('id')
            ->get();

        return view('staff.students.show', [
            'student' => $student,
            'bookings' => $bookings->concat($online)->sortByDesc(fn ($row) => $row['model']->created_at),
            'quotations' => $quotations,
            'routePrefix' => $this->panelRoutePrefix(),
        ]);
    }
}
