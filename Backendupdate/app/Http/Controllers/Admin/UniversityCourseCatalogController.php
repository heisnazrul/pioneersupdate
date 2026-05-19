<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\SubjectArea;
use App\Models\UniversityCourseCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UniversityCourseCatalogController extends Controller
{
    public function index(): View
    {
        $catalogs = UniversityCourseCatalog::with(['subjectArea', 'levels'])->orderBy('name')->paginate(20);

        return view('admin.university-course-catalogs.index', compact('catalogs'));
    }

    public function create(): View
    {
        $subjects = SubjectArea::where('is_active', true)->orderBy('sort_order')->get();
        $levels = Level::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.university-course-catalogs.create', compact('subjects', 'levels'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $catalog = UniversityCourseCatalog::create($data);
        $catalog->levels()->sync($request->input('levels', []));

        return redirect()->route('admin.university-course-catalogs.index')->with('success', 'Course catalog created successfully.');
    }

    public function edit(UniversityCourseCatalog $universityCourseCatalog): View
    {
        $subjects = SubjectArea::where('is_active', true)->orderBy('sort_order')->get();
        $levels = Level::where('is_active', true)->orderBy('sort_order')->get();
        $selectedLevels = $universityCourseCatalog->levels()->pluck('levels.id')->all();

        return view('admin.university-course-catalogs.edit', compact('universityCourseCatalog', 'subjects', 'levels', 'selectedLevels'));
    }

    public function update(Request $request, UniversityCourseCatalog $universityCourseCatalog): RedirectResponse
    {
        $data = $this->validateData($request, $universityCourseCatalog);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $universityCourseCatalog->update($data);
        $universityCourseCatalog->levels()->sync($request->input('levels', []));

        return redirect()->route('admin.university-course-catalogs.index')->with('success', 'Course catalog updated successfully.');
    }

    public function destroy(UniversityCourseCatalog $universityCourseCatalog): RedirectResponse
    {
        $universityCourseCatalog->delete();

        return redirect()->route('admin.university-course-catalogs.index')->with('success', 'Course catalog deleted successfully.');
    }

    private function validateData(Request $request, ?UniversityCourseCatalog $catalog = null): array
    {
        return $request->validate([
            'subject_area_id' => ['nullable', 'exists:subject_areas,id'],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:300'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('university_course_catalogs', 'slug')->ignore($catalog?->id)],
            'levels' => ['nullable', 'array'],
            'levels.*' => ['integer', 'exists:levels,id'],
        ]);
    }
}
