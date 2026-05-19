<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseOnlineCourse;
use App\Models\LanguageCourseTag;
use App\Models\LanguageCourseType;
use App\Models\LanguageSchool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class LanguageCourseOnlineCourseController extends Controller
{
    public function index(): View
    {
        $courses = LanguageCourseOnlineCourse::query()
            ->with(['school', 'courseType', 'tag'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-course-online-courses.index', compact('courses'));
    }

    public function create(): View
    {
        return view('admin.language-course-online-courses.create', [
            'schools' => $this->schools(),
            'types' => $this->types(),
            'tags' => $this->tags(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = !empty($data['slug'])
            ? Str::slug($data['slug'])
            : Str::slug($data['name'].'-'.uniqid());

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('language-online-courses', 'public');
        }

        LanguageCourseOnlineCourse::create($data);

        return redirect()->route('admin.language-course-online-courses.index')
            ->with('success', 'Online course created successfully.');
    }

    public function edit(LanguageCourseOnlineCourse $languageCourseOnlineCourse): View
    {
        return view('admin.language-course-online-courses.edit', [
            'course' => $languageCourseOnlineCourse,
            'schools' => $this->schools(),
            'types' => $this->types(),
            'tags' => $this->tags(),
        ]);
    }

    public function update(Request $request, LanguageCourseOnlineCourse $languageCourseOnlineCourse): RedirectResponse
    {
        $data = $this->validateData($request, $languageCourseOnlineCourse);
        $data['slug'] = !empty($data['slug'])
            ? Str::slug($data['slug'])
            : $languageCourseOnlineCourse->slug;

        if ($request->hasFile('thumbnail')) {
            if ($languageCourseOnlineCourse->thumbnail) {
                Storage::disk('public')->delete($languageCourseOnlineCourse->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('language-online-courses', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $languageCourseOnlineCourse->update($data);

        return redirect()->route('admin.language-course-online-courses.index')
            ->with('success', 'Online course updated successfully.');
    }

    public function destroy(LanguageCourseOnlineCourse $languageCourseOnlineCourse): RedirectResponse
    {
        $languageCourseOnlineCourse->delete();
        return redirect()->route('admin.language-course-online-courses.index')
            ->with('success', 'Online course deleted.');
    }

    private function validateData(Request $request, ?LanguageCourseOnlineCourse $course = null): array
    {
        return $request->validate([
            'slug' => ['nullable', 'string', 'max:160', 'unique:language_course_online_courses,slug'.($course ? ','.$course->id : '')],
            'language_school_id' => ['required', 'exists:language_schools,id'],
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

    private function types()
    {
        return LanguageCourseType::orderBy('name')->get(['id', 'name']);
    }

    private function tags()
    {
        return LanguageCourseTag::orderBy('name')->get(['id', 'name']);
    }
}
