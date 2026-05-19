<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectArea;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectAreaController extends Controller
{
    public function index()
    {
        $subjectAreas = SubjectArea::orderBy('sort_order')->orderBy('name')->paginate(10);
        return view('admin.subject_areas.index', compact('subjectAreas'));
    }

    public function create()
    {
        return view('admin.subject_areas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:80|unique:subject_areas,key',
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:140|unique:subject_areas,slug',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        SubjectArea::create($validated);

        return redirect()->route('admin.subject-areas.index')->with('success', 'Subject Area created successfully.');
    }

    public function edit(SubjectArea $subjectArea)
    {
        return view('admin.subject_areas.edit', compact('subjectArea'));
    }

    public function update(Request $request, SubjectArea $subjectArea)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:80|unique:subject_areas,key,' . $subjectArea->id,
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:140|unique:subject_areas,slug,' . $subjectArea->id,
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $subjectArea->update($validated);

        return redirect()->route('admin.subject-areas.index')->with('success', 'Subject Area updated successfully.');
    }

    public function destroy(SubjectArea $subjectArea)
    {
        $subjectArea->delete();
        return redirect()->route('admin.subject-areas.index')->with('success', 'Subject Area deleted successfully.');
    }
}
