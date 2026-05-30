<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ContactSubmission;
use App\Models\Country;
use App\Models\LanguageCourseBooking;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolCourse;
use App\Models\OnlineCourseBooking;
use App\Models\PayoutRequest;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            [
                'label' => 'Language Schools',
                'value' => LanguageSchool::where('status', 'active')->count(),
                'total' => LanguageSchool::count(),
                'icon' => 'fa-school-flag',
                'color' => 'blue',
                'href' => route('admin.language-schools.index'),
            ],
            [
                'label' => 'School Courses',
                'value' => LanguageSchoolCourse::where('is_active', 'yes')->count(),
                'total' => LanguageSchoolCourse::count(),
                'icon' => 'fa-book-open',
                'color' => 'indigo',
                'href' => route('admin.language-school-courses.index'),
            ],
            [
                'label' => 'CourseSat Bookings',
                'value' => LanguageCourseBooking::count() + OnlineCourseBooking::count(),
                'total' => null,
                'icon' => 'fa-calendar-check',
                'color' => 'emerald',
                'href' => route('admin.course-sat-bookings.index'),
            ],
            [
                'label' => 'Active Students',
                'value' => User::whereIn('role', ['lg_student', 'uni_student'])->where('status', 'active')->count(),
                'total' => User::whereIn('role', ['lg_student', 'uni_student'])->count(),
                'icon' => 'fa-users',
                'color' => 'purple',
                'href' => route('admin.users.index'),
            ],
            [
                'label' => 'Branches',
                'value' => LanguageSchoolBranch::where('is_active', 'yes')->count(),
                'total' => LanguageSchoolBranch::count(),
                'icon' => 'fa-code-branch',
                'color' => 'cyan',
                'href' => route('admin.language-school-branches.index'),
            ],
            [
                'label' => 'Countries',
                'value' => Country::where('is_active', true)->count(),
                'total' => Country::count(),
                'icon' => 'fa-flag',
                'color' => 'rose',
                'href' => route('admin.countries.index'),
            ],
            [
                'label' => 'Applications',
                'value' => Application::count(),
                'total' => Application::where('status', 'pending')->count() . ' pending',
                'icon' => 'fa-file-signature',
                'color' => 'amber',
                'href' => route('admin.applications.index'),
            ],
            [
                'label' => 'Pending Inquiries',
                'value' => ContactSubmission::where('status', 'pending')->count(),
                'total' => ContactSubmission::count() . ' total',
                'icon' => 'fa-envelope',
                'color' => 'orange',
                'href' => route('admin.contact-submissions.index'),
            ],
        ];

        $alerts = array_filter([
            PayoutRequest::where('status', 'pending')->count() > 0
                ? ['label' => 'Pending payout requests', 'count' => PayoutRequest::where('status', 'pending')->count(), 'href' => route('admin.payout-requests.index'), 'icon' => 'fa-money-check-dollar']
                : null,
            LanguageCourseBooking::where('status', 'pending')->count() > 0
                ? ['label' => 'Pending language bookings', 'count' => LanguageCourseBooking::where('status', 'pending')->count(), 'href' => route('admin.course-sat-bookings.index'), 'icon' => 'fa-clock']
                : null,
        ]);

        $recentBookings = LanguageCourseBooking::query()
            ->with(['school:id,name_en', 'course:id,course_name_from_school'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $recentApplications = Application::query()
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'alerts', 'recentBookings', 'recentApplications'));
    }
}
