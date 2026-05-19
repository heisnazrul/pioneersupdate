<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityCourseTag;
use Illuminate\Http\Request;

class UniversityCourseTagController extends Controller
{
    public function index()
    {
        $tags = UniversityCourseTag::orderBy('name')->paginate(10);
        return view('admin.course_tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.course_tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:60|unique:university_course_tags,key',
            'name' => 'required|string|max:80',
            'is_active' => 'boolean',
        ]);

        UniversityCourseTag::create($validated);

        return redirect()->route('admin.course-tags.index')->with('success', 'Course Tag created successfully.');
    }

    public function edit(UniversityCourseTag $courseTag)
    {
        return view('admin.course_tags.edit', compact('courseTag'));
    }

    public function update(Request $request, UniversityCourseTag $courseTag)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:60|unique:university_course_tags,key,' . $courseTag->id,
            'name' => 'required|string|max:80',
            'is_active' => 'boolean',
        ]);

        $courseTag->update($validated);

        return redirect()->route('admin.course-tags.index')->with('success', 'Course Tag updated successfully.');
    }

    public function destroy(UniversityCourseTag $courseTag)
    {
        $courseTag->delete();
        return redirect()->route('admin.course-tags.index')->with('success', 'Course Tag deleted successfully.');
    }
}
