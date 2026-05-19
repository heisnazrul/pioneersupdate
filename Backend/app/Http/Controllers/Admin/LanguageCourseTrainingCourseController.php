<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseTrainingCourse;
use App\Models\LanguageCourseTag;
use App\Models\LanguageCourseType;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class LanguageCourseTrainingCourseController extends Controller
{
    public function index(): View
    {
        $courses = LanguageCourseTrainingCourse::query()
            ->with(['school', 'branch', 'courseType', 'tag'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-course-training-courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('admin.language-course-training-courses.create', [
            'schools' => $this->schools(),
            'branches' => $this->branches(),
            'types' => $this->types(),
            'tags' => $this->tags(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('language-training-courses', 'public');
        }

        LanguageCourseTrainingCourse::create($data);

        return redirect()->route('admin.language-course-training-courses.index')
            ->with('success', 'Training course created successfully.');
    }

    public function edit(LanguageCourseTrainingCourse $languageCourseTrainingCourse): View
    {
        return view('admin.language-course-training-courses.edit', [
            'course' => $languageCourseTrainingCourse,
            'schools' => $this->schools(),
            'branches' => $this->branches(),
            'types' => $this->types(),
            'tags' => $this->tags(),
        ]);
    }

    public function update(Request $request, LanguageCourseTrainingCourse $languageCourseTrainingCourse): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('thumbnail')) {
            if (!empty($languageCourseTrainingCourse->thumbnail)) {
                Storage::disk('public')->delete($languageCourseTrainingCourse->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('language-training-courses', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $languageCourseTrainingCourse->update($data);

        return redirect()->route('admin.language-course-training-courses.index')
            ->with('success', 'Training course updated successfully.');
    }

    public function destroy(LanguageCourseTrainingCourse $languageCourseTrainingCourse): RedirectResponse
    {
        if (!empty($languageCourseTrainingCourse->thumbnail)) {
            Storage::disk('public')->delete($languageCourseTrainingCourse->thumbnail);
        }

        $languageCourseTrainingCourse->delete();
        return redirect()->route('admin.language-course-training-courses.index')
            ->with('success', 'Training course deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'language_school_id' => ['required', 'exists:language_schools,id'],
            'branch_id' => ['nullable', 'exists:language_school_branches,id'],
            'course_type_id' => ['required', 'exists:language_course_types,id'],
            'tag_id' => ['nullable', 'exists:language_course_tags,id'],
            'name' => ['required', 'string', 'max:200'],
            'ar_name' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'required_level' => ['nullable', 'string', 'max:10'],
            'study_time' => ['nullable', 'string', 'max:10'],
            'lessons_per_week' => ['nullable', 'integer', 'min:0'],
            'min_age' => ['nullable', 'integer', 'min:0'],
            'start_date' => ['nullable', 'string', 'max:10'],
            'fee_type' => ['required', 'in:flat,weekly'],
            'fee_amount' => ['required', 'numeric', 'min:0'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'visible' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published,suspended'],
        ]);
    }

    private function schools()
    {
        return LanguageSchool::orderBy('name')->get(['id', 'name']);
    }

    private function branches()
    {
        return LanguageSchoolBranch::orderBy('id')->get(['id', 'language_school_id', 'slug']);
    }

    private function types()
    {
        return LanguageCourseType::orderBy('name')->get(['id', 'name']);
    }

    private function tags()
    {
        return LanguageCourseTag::orderBy('name')->get(['id', 'name']);
    }
}
