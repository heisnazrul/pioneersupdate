<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\LanguageCourseCategory;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageCourseTrainingCourseController extends Controller
{
    public function index(): View
    {
        $courses = LanguageCourseTrainingCourse::with(['school', 'branch.city', 'courseType'])
            ->latest()
            ->paginate(20);

        return view('admin.language-course-training-courses.index', compact('courses'));
    }

    public function create(): View
    {
        $schools = LanguageSchool::active()->orderBy('name_en')->get();
        $branches = LanguageSchoolBranch::with(['school', 'city'])
            ->where('is_active', 'yes')
            ->get();
        $categories = LanguageCourseCategory::active()->orderBy('name_en')->get();
        $thumbnails = Gallery::where('use_case', 'training')->get();

        return view('admin.language-course-training-courses.create', compact('schools', 'branches', 'categories', 'thumbnails'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['thumbnail'] = $request->input('gallery_thumbnail');
        unset($data['gallery_thumbnail']);
        $data['visible'] = $request->boolean('visible', true);

        LanguageCourseTrainingCourse::create($data);

        return redirect()->route('admin.language-course-training-courses.index')
            ->with('success', 'Training course created successfully.');
    }

    public function edit(LanguageCourseTrainingCourse $languageCourseTrainingCourse): View
    {
        $schools = LanguageSchool::active()->orderBy('name_en')->get();
        $branches = LanguageSchoolBranch::with(['school', 'city'])->get();
        $categories = LanguageCourseCategory::active()->orderBy('name_en')->get();
        $thumbnails = Gallery::where('use_case', 'training')->get();

        return view('admin.language-course-training-courses.edit', compact('languageCourseTrainingCourse', 'schools', 'branches', 'categories', 'thumbnails'));
    }

    public function update(Request $request, LanguageCourseTrainingCourse $languageCourseTrainingCourse): RedirectResponse
    {
        $data = $this->validateRequest($request);
        $data['thumbnail'] = $request->input('gallery_thumbnail');
        unset($data['gallery_thumbnail']);
        $data['visible'] = $request->boolean('visible');

        $languageCourseTrainingCourse->update($data);

        return redirect()->route('admin.language-course-training-courses.index')
            ->with('success', 'Training course updated successfully.');
    }

    public function destroy(LanguageCourseTrainingCourse $languageCourseTrainingCourse): RedirectResponse
    {
        $languageCourseTrainingCourse->delete();

        return redirect()->route('admin.language-course-training-courses.index')
            ->with('success', 'Training course deleted.');
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'language_school_id' => ['required', 'exists:language_schools,id'],
            'branch_id' => [
                'nullable',
                'exists:language_school_branches,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if (!$value || !$request->filled('language_school_id')) {
                        return;
                    }

                    $belongsToSchool = LanguageSchoolBranch::query()
                        ->whereKey($value)
                        ->where('school_id', $request->input('language_school_id'))
                        ->exists();

                    if (!$belongsToSchool) {
                        $fail('The selected branch does not belong to the selected school.');
                    }
                },
            ],
            'course_type_id' => ['required', 'exists:language_course_categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'ar_name' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'required_level' => ['nullable', 'string', 'max:10'],
            'study_time' => ['nullable', 'string', 'max:10'],
            'lessons_per_week' => ['nullable', 'integer', 'min:0'],
            'min_age' => ['nullable', 'integer', 'min:0', 'max:255'],
            'start_date' => ['nullable', 'string', 'max:10'],
            'fee_type' => ['required', Rule::in(['flat', 'weekly'])],
            'fee_amount' => ['required', 'numeric', 'min:0'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'gallery_thumbnail' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published', 'suspended'])],
        ]);
    }
}
