<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolCourseMaterialFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageSchoolCourseMaterialFeeController extends Controller
{
    public function index(): View
    {
        $filters = [
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'school_id' => request('school_id'),
            'branch_id' => request('branch_id'),
            'billing_unit' => request('billing_unit'),
        ];

        $fees = LanguageSchoolCourseMaterialFee::with('course.branch.school')
            ->when($filters['country_id'], fn($q) => $q->whereHas('course.branch.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('course.branch', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->when($filters['school_id'], fn($q) => $q->whereHas('course.branch', fn($qq) => $qq->where('language_school_id', $filters['school_id'])))
            ->when($filters['branch_id'], fn($q) => $q->whereHas('course', fn($qq) => $qq->where('branch_id', $filters['branch_id'])))
            ->when($filters['billing_unit'], fn($q) => $q->where('billing_unit', $filters['billing_unit']))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $countries = \App\Models\Country::whereHas('cities.languageSchoolBranches.languageSchoolCourses.materialFees')->orderBy('name')->get(['id','name']);
        $cities = \App\Models\City::whereHas('languageSchoolBranches.languageSchoolCourses.materialFees')
            ->when($filters['country_id'], fn($q) => $q->where('country_id', $filters['country_id']))
            ->orderBy('name')->get(['id','name','country_id']);
        $schools = \App\Models\LanguageSchool::whereHas('branches.languageSchoolCourses.materialFees')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branches.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branches', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->orderBy('name')->get(['id','name']);
        $branches = \App\Models\LanguageSchoolBranch::whereHas('courses.materialFees')
            ->when($filters['school_id'], fn($q) => $q->where('language_school_id', $filters['school_id']))
            ->when($filters['city_id'], fn($q) => $q->where('city_id', $filters['city_id']))
            ->when($filters['country_id'], fn($q) => $q->whereHas('city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->with('school')
            ->orderBy('slug')
            ->get(['id','slug','language_school_id','city_id']);

        return view('admin.language-school-course-material-fees.index', compact('fees','filters','countries','cities','schools','branches'));
    }

    public function create(): View
    {
        $selectedCourseId = (int) request('language_school_course_id', request('language_course_id', 0));
        $courses = $this->courseOptions();

        return view('admin.language-school-course-material-fees.create', compact('courses', 'selectedCourseId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        LanguageSchoolCourseMaterialFee::create($data);

        return redirect()->route('admin.language-school-course-material-fees.index')->with('success', 'Material fee created.');
    }

    public function edit(LanguageSchoolCourseMaterialFee $languageSchoolCourseMaterialFee): View
    {
        $courses = $this->courseOptions();

        return view('admin.language-school-course-material-fees.edit', [
            'fee' => $languageSchoolCourseMaterialFee,
            'courses' => $courses,
        ]);
    }

    public function update(Request $request, LanguageSchoolCourseMaterialFee $languageSchoolCourseMaterialFee): RedirectResponse
    {
        $data = $this->validateData($request);
        $languageSchoolCourseMaterialFee->update($data);

        return redirect()->route('admin.language-school-course-material-fees.index')->with('success', 'Material fee updated.');
    }

    public function destroy(LanguageSchoolCourseMaterialFee $languageSchoolCourseMaterialFee): RedirectResponse
    {
        $languageSchoolCourseMaterialFee->delete();
        return redirect()->route('admin.language-school-course-material-fees.index')->with('success', 'Material fee deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'language_school_course_id' => ['required', 'exists:language_school_courses,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'billing_unit' => ['required', Rule::in(['week', 'month', 'course'])],
            'billing_count' => ['required', 'integer', 'min:1'],
        ]);
    }

    private function courseOptions()
    {
        return LanguageSchoolCourse::query()
            ->with(['branch.school:id,name', 'branch.city:id,name'])
            ->withCount('materialFees')
            ->withMax('materialFees as material_fees_last_updated_at', 'updated_at')
            ->orderBy('name')
            ->get();
    }
}
