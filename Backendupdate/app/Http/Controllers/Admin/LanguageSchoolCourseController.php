<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageCourseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LanguageSchoolCourseController extends Controller
{
    public function index(): View
    {
        $courses = LanguageSchoolCourse::with(['branch.school', 'category'])->latest()->paginate(20);
        return view('admin.language-school-courses.index', compact('courses'));
    }

    public function create(): View
    {
        $branches = LanguageSchoolBranch::with('school')->get();
        $categories = LanguageCourseCategory::active()->get();
        return view('admin.language-school-courses.create', compact('branches', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'branch_id'                     => 'required|exists:language_school_branches,id',
            'course_category_id'            => 'required|exists:language_course_categories,id',
            'course_name_from_school'       => 'required|string|max:255',
            'course_name_from_school_ar'    => 'nullable|string|max:255',
            'slug'                          => 'nullable|string|max:255',
            'hours_per_week'                => 'nullable|numeric|min:0',
            'lessons_per_week'              => 'nullable|integer|min:0',
            'min_level'                     => 'nullable|string|max:255',
            'min_age'                       => 'nullable|integer|min:0',
            'material_books_fee'            => 'required|numeric|min:0',
            'registration_admin_fee'        => 'required|numeric|min:0',
            'mandatory_additional_fee_name' => 'nullable|string|max:255',
            'mandatory_additional_fee'      => 'required|numeric|min:0',
            'week_category_1'               => 'nullable|integer',
            'weekly_fee_1'                  => 'nullable|numeric',
            'week_category_2'               => 'nullable|integer',
            'weekly_fee_2'                  => 'nullable|numeric',
            'week_category_3'               => 'nullable|integer',
            'weekly_fee_3'                  => 'nullable|numeric',
            'week_category_4'               => 'nullable|integer',
            'weekly_fee_4'                  => 'nullable|numeric',
            'week_category_5'               => 'nullable|integer',
            'weekly_fee_5'                  => 'nullable|numeric',
            'week_category_6'               => 'nullable|integer',
            'weekly_fee_6'                  => 'nullable|numeric',
            'week_category_7'               => 'nullable|integer',
            'weekly_fee_7'                  => 'nullable|numeric',
            'is_active'                     => 'required|in:yes,no',
        ]);

        if (!$request->filled('slug')) {
            $data['slug'] = Str::slug($data['course_name_from_school']);
        }

        LanguageSchoolCourse::create($data);

        return redirect()->route('admin.language-school-courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(LanguageSchoolCourse $languageSchoolCourse): View
    {
        $branches = LanguageSchoolBranch::with('school')->get();
        $categories = LanguageCourseCategory::all();
        return view('admin.language-school-courses.edit', compact('languageSchoolCourse', 'branches', 'categories'));
    }

    public function update(Request $request, LanguageSchoolCourse $languageSchoolCourse): RedirectResponse
    {
        $data = $request->validate([
            'branch_id'                     => 'required|exists:language_school_branches,id',
            'course_category_id'            => 'required|exists:language_course_categories,id',
            'course_name_from_school'       => 'required|string|max:255',
            'course_name_from_school_ar'    => 'nullable|string|max:255',
            'slug'                          => 'required|string|max:255',
            'hours_per_week'                => 'nullable|numeric|min:0',
            'lessons_per_week'              => 'nullable|integer|min:0',
            'min_level'                     => 'nullable|string|max:255',
            'min_age'                       => 'nullable|integer|min:0',
            'material_books_fee'            => 'required|numeric|min:0',
            'registration_admin_fee'        => 'required|numeric|min:0',
            'mandatory_additional_fee_name' => 'nullable|string|max:255',
            'mandatory_additional_fee'      => 'required|numeric|min:0',
            'week_category_1'               => 'nullable|integer',
            'weekly_fee_1'                  => 'nullable|numeric',
            'week_category_2'               => 'nullable|integer',
            'weekly_fee_2'                  => 'nullable|numeric',
            'week_category_3'               => 'nullable|integer',
            'weekly_fee_3'                  => 'nullable|numeric',
            'week_category_4'               => 'nullable|integer',
            'weekly_fee_4'                  => 'nullable|numeric',
            'week_category_5'               => 'nullable|integer',
            'weekly_fee_5'                  => 'nullable|numeric',
            'week_category_6'               => 'nullable|integer',
            'weekly_fee_6'                  => 'nullable|numeric',
            'week_category_7'               => 'nullable|integer',
            'weekly_fee_7'                  => 'nullable|numeric',
            'is_active'                     => 'required|in:yes,no',
        ]);

        $languageSchoolCourse->update($data);

        return redirect()->route('admin.language-school-courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(LanguageSchoolCourse $languageSchoolCourse): RedirectResponse
    {
        $languageSchoolCourse->delete();
        return redirect()->route('admin.language-school-courses.index')
            ->with('success', 'Course deleted.');
    }
}
