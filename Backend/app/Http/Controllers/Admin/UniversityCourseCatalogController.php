<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\SubjectArea;
use App\Models\UniversityCourseCatalog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UniversityCourseCatalogController extends Controller
{
    public function index()
    {
        $catalogs = UniversityCourseCatalog::with(['subjectArea', 'levels'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.university_course_catalogs.index', compact('catalogs'));
    }

    public function create()
    {
        $subjects = SubjectArea::where('is_active', true)->orderBy('sort_order')->get();
        $levels = Level::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.university_course_catalogs.create', compact('subjects', 'levels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:300'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:university_course_catalogs,slug'],
            'subject_area_id' => ['nullable', 'exists:subject_areas,id'],
            'is_active' => ['nullable', 'boolean'],
            'levels' => ['nullable', 'array'],
            'levels.*' => ['integer', 'exists:levels,id'],
        ]);

        $catalog = UniversityCourseCatalog::create([
            'name' => $validated['name'],
            'ar_name' => $validated['ar_name'] ?? null,
            'slug' => $validated['slug'] ?? null,
            'subject_area_id' => $validated['subject_area_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $catalog->levels()->sync($validated['levels'] ?? []);

        return redirect()->route('admin.university-course-catalogs.index')
            ->with('success', 'Course catalog created successfully.');
    }

    public function edit(UniversityCourseCatalog $universityCourseCatalog)
    {
        $subjects = SubjectArea::where('is_active', true)->orderBy('sort_order')->get();
        $levels = Level::where('is_active', true)->orderBy('sort_order')->get();
        $selectedLevels = $universityCourseCatalog->levels()->pluck('levels.id')->all();

        return view('admin.university_course_catalogs.edit', compact('universityCourseCatalog', 'subjects', 'levels', 'selectedLevels'));
    }

    public function update(Request $request, UniversityCourseCatalog $universityCourseCatalog)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:300'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('university_course_catalogs', 'slug')->ignore($universityCourseCatalog->id)],
            'subject_area_id' => ['nullable', 'exists:subject_areas,id'],
            'is_active' => ['nullable', 'boolean'],
            'levels' => ['nullable', 'array'],
            'levels.*' => ['integer', 'exists:levels,id'],
        ]);

        $universityCourseCatalog->update([
            'name' => $validated['name'],
            'ar_name' => $validated['ar_name'] ?? null,
            'slug' => $validated['slug'] ?? null,
            'subject_area_id' => $validated['subject_area_id'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $universityCourseCatalog->levels()->sync($validated['levels'] ?? []);

        return redirect()->route('admin.university-course-catalogs.index')
            ->with('success', 'Course catalog updated successfully.');
    }

    public function destroy(UniversityCourseCatalog $universityCourseCatalog)
    {
        $universityCourseCatalog->delete();

        return redirect()->route('admin.university-course-catalogs.index')
            ->with('success', 'Course catalog deleted successfully.');
    }
}
