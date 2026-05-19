<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseTag;
use App\Models\LanguageCourseType;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolCourse;
use App\Models\LanguageSchool;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageSchoolCourseController extends Controller
{
    public function index(): View
    {
        $query = LanguageSchoolCourse::with(['branch.school', 'branch.city.country', 'type', 'tag']);

        $filters = [
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'school_id' => request('school_id'),
            'branch_id' => request('branch_id'),
            'type_id' => request('type_id'),
            'tag_id' => request('tag_id'),
        ];

        if ($filters['country_id']) {
            $query->whereHas('branch.city', fn ($q) => $q->where('country_id', $filters['country_id']));
        }
        if ($filters['city_id']) {
            $query->whereHas('branch', fn ($q) => $q->where('city_id', $filters['city_id']));
        }
        if ($filters['school_id']) {
            $query->whereHas('branch', fn ($q) => $q->where('language_school_id', $filters['school_id']));
        }
        if ($filters['branch_id']) {
            $query->where('branch_id', $filters['branch_id']);
        }
        if ($filters['type_id']) {
            $query->where('language_course_type_id', $filters['type_id']);
        }
        if ($filters['tag_id']) {
            $query->where('language_course_tag_id', $filters['tag_id']);
        }

        $courses = $query->orderBy('name')->paginate(15)->withQueryString();

        $countries = Country::whereHas('cities.languageSchoolBranches.languageSchoolCourses')->orderBy('name')->get(['id', 'name']);
        $cities = City::whereHas('languageSchoolBranches.languageSchoolCourses')
            ->when($filters['country_id'], fn ($q) => $q->where('country_id', $filters['country_id']))
            ->orderBy('name')->get(['id', 'name', 'country_id']);
        $schools = LanguageSchool::whereHas('branches.languageSchoolCourses')
            ->when($filters['country_id'], fn ($q) => $q->whereHas('branches.city', fn ($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn ($q) => $q->whereHas('branches', fn ($qq) => $qq->where('city_id', $filters['city_id'])))
            ->orderBy('name')->get(['id', 'name']);
        $branches = LanguageSchoolBranch::whereHas('courses')
            ->when($filters['school_id'], fn ($q) => $q->where('language_school_id', $filters['school_id']))
            ->when($filters['city_id'], fn ($q) => $q->where('city_id', $filters['city_id']))
            ->when($filters['country_id'], fn ($q) => $q->whereHas('city', fn ($qq) => $qq->where('country_id', $filters['country_id'])))
            ->with('school')
            ->orderBy('slug')
            ->get(['id', 'slug', 'language_school_id', 'city_id']);

        $types = LanguageCourseType::orderBy('name')->get(['id', 'name']);
        $tags = LanguageCourseTag::orderBy('name')->get(['id', 'name']);

        return view('admin.language-school-courses.index', compact(
            'courses',
            'filters',
            'countries',
            'cities',
            'schools',
            'branches',
            'types',
            'tags'
        ));
    }

    public function create(): View
    {
        return view('admin.language-school-courses.create', [
            'branches' => LanguageSchoolBranch::with('school')->orderBy('slug')->get(),
            'types' => LanguageCourseType::orderBy('name')->get(),
            'tags' => LanguageCourseTag::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        LanguageSchoolCourse::create($data);

        return redirect()->route('admin.language-school-courses.index')->with('success', 'Language course created successfully.');
    }

    public function edit(LanguageSchoolCourse $languageSchoolCourse): View
    {
        return view('admin.language-school-courses.edit', [
            'course' => $languageSchoolCourse,
            'branches' => LanguageSchoolBranch::with('school')->orderBy('slug')->get(),
            'types' => LanguageCourseType::orderBy('name')->get(),
            'tags' => LanguageCourseTag::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LanguageSchoolCourse $languageSchoolCourse): RedirectResponse
    {
        $data = $this->validateData($request, $languageSchoolCourse);
        $languageSchoolCourse->update($data);

        return redirect()->route('admin.language-school-courses.index')->with('success', 'Language course updated successfully.');
    }

    public function destroy(LanguageSchoolCourse $languageSchoolCourse): RedirectResponse
    {
        $languageSchoolCourse->delete();
        return redirect()->route('admin.language-school-courses.index')->with('success', 'Language course deleted successfully.');
    }

    private function validateData(Request $request, ?LanguageSchoolCourse $languageSchoolCourse = null): array
    {
        $slugInput = $request->input('slug') ?: $request->input('name');
        $request->merge([
            'slug' => $slugInput ? Str::slug($slugInput) : null,
        ]);

        return $request->validate([
            'branch_id' => ['required', 'integer', 'exists:language_school_branches,id'],
            'language_course_type_id' => ['required', 'integer', 'exists:language_course_types,id'],
            'language_course_tag_id' => ['nullable', 'integer', 'exists:language_course_tags,id'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('language_school_courses', 'slug')->ignore($languageSchoolCourse?->id)],
            'name' => ['required', 'string', 'max:200'],
            'ar_name' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'start_day' => ['nullable', 'string', 'max:32'],
            'required_level' => ['nullable', 'string', 'max:32'],
            'study_time' => ['nullable', 'string', 'max:30'],
            'lessons_per_week' => ['nullable', 'string', 'max:30'],
            'min_age' => ['nullable', 'string', 'max:30'],
        ]);
    }
}
