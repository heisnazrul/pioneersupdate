<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Counsellor\StudentController as CounsellorStudentController;
use App\Models\LanguageCourseBooking;
use App\Models\OnlineCourseBooking;
use App\Models\Quotation;
use App\Models\User;
use App\Services\Staff\StaffAssignmentService;
use App\Services\Staff\StaffStudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends CounsellorStudentController
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

    public function index(Request $request, StaffStudentService $service): View
    {
        return view('staff.students.index', [
            'students' => $service->paginate($request, null),
            'routePrefix' => $this->panelRoutePrefix(),
            'showAssign' => true,
            'counsellors' => $this->counsellors(),
            'activeCounsellor' => $request->query('counsellor_id'),
            'search' => $request->query('search', ''),
        ]);
    }

    public function show(User $student, Request $request): View
    {
        abort_unless($student->role === 'lg_student', 404);

        $bookings = LanguageCourseBooking::query()
            ->where('user_id', $student->id)
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn ($b) => ['type' => 'language_course', 'model' => $b]);

        $online = OnlineCourseBooking::query()
            ->where('user_id', $student->id)
            ->latest('id')
            ->limit(20)
            ->get()
            ->map(fn ($b) => ['type' => 'online_course', 'model' => $b]);

        $quotations = Quotation::query()
            ->where('student_user_id', $student->id)
            ->latest('id')
            ->get();

        return view('staff.students.show', [
            'student' => $student,
            'bookings' => $bookings->concat($online)->sortByDesc(fn ($row) => $row['model']->created_at),
            'quotations' => $quotations,
            'routePrefix' => $this->panelRoutePrefix(),
            'showAssign' => true,
            'counsellors' => $this->counsellors(),
        ]);
    }

    public function assign(Request $request, User $student, StaffAssignmentService $assignments): RedirectResponse
    {
        abort_unless($student->role === 'lg_student', 404);

        $data = $request->validate([
            'assigned_to' => ['required', 'exists:users,id'],
        ]);

        $assignments->assignStudent($student->id, (int) $data['assigned_to'], $request->user());

        return back()->with('success', 'Student assigned to counsellor.');
    }
}
