<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseCategory;
use App\Models\Gallery;
use App\Models\LanguageOnlineCourse;
use App\Models\LanguageSchool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageOnlineCourseController extends Controller
{
    public function index(): View
    {
        $courses = LanguageOnlineCourse::with(['school', 'courseType'])->latest()->paginate(20);

        return view('admin.language-online-courses.index', compact('courses'));
    }

    public function create(): View
    {
        $schools = LanguageSchool::active()->orderBy('name_en')->get();
        $categories = LanguageCourseCategory::active()->orderBy('name_en')->get();
        $thumbnails = Gallery::where('use_case', 'online_course')->get();

        return view('admin.language-online-courses.create', compact('schools', 'categories', 'thumbnails'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        if (!$request->filled('slug')) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        }

        $data['thumbnail'] = $request->input('gallery_thumbnail');
        unset($data['gallery_thumbnail']);
        $data['visible'] = $request->boolean('visible', true);

        LanguageOnlineCourse::create($data);

        return redirect()->route('admin.language-online-courses.index')
            ->with('success', 'Online course created successfully.');
    }

    public function edit(LanguageOnlineCourse $languageOnlineCourse): View
    {
        $schools = LanguageSchool::active()->orderBy('name_en')->get();
        $categories = LanguageCourseCategory::active()->orderBy('name_en')->get();
        $thumbnails = Gallery::where('use_case', 'online_course')->get();

        return view('admin.language-online-courses.edit', compact('languageOnlineCourse', 'schools', 'categories', 'thumbnails'));
    }

    public function update(Request $request, LanguageOnlineCourse $languageOnlineCourse): RedirectResponse
    {
        $data = $this->validateRequest($request, $languageOnlineCourse);

        if (!$request->filled('slug')) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $languageOnlineCourse->id);
        }

        $data['thumbnail'] = $request->input('gallery_thumbnail');
        unset($data['gallery_thumbnail']);
        $data['visible'] = $request->boolean('visible');

        $languageOnlineCourse->update($data);

        return redirect()->route('admin.language-online-courses.index')
            ->with('success', 'Online course updated successfully.');
    }

    public function destroy(LanguageOnlineCourse $languageOnlineCourse): RedirectResponse
    {
        $languageOnlineCourse->delete();

        return redirect()->route('admin.language-online-courses.index')
            ->with('success', 'Online course deleted.');
    }

    private function validateRequest(Request $request, ?LanguageOnlineCourse $course = null): array
    {
        return $request->validate([
            'slug' => [
                'nullable',
                'string',
                'max:160',
                Rule::unique('language_online_courses', 'slug')->ignore($course?->id),
            ],
            'language_school_id' => ['required', 'exists:language_schools,id'],
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

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'online-course';
        $slug = $baseSlug;
        $counter = 1;

        while (LanguageOnlineCourse::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
