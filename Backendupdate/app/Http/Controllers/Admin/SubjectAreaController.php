<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubjectArea;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectAreaController extends Controller
{
    public function index(): View
    {
        $subjectAreas = SubjectArea::orderBy('sort_order')->orderBy('name')->paginate(15);

        return view('admin.subject-areas.index', compact('subjectAreas'));
    }

    public function create(): View
    {
        return view('admin.subject-areas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        SubjectArea::create($data);

        return redirect()->route('admin.subject-areas.index')->with('success', 'Subject area created successfully.');
    }

    public function edit(SubjectArea $subjectArea): View
    {
        return view('admin.subject-areas.edit', compact('subjectArea'));
    }

    public function update(Request $request, SubjectArea $subjectArea): RedirectResponse
    {
        $data = $this->validateData($request, $subjectArea);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        $subjectArea->update($data);

        return redirect()->route('admin.subject-areas.index')->with('success', 'Subject area updated successfully.');
    }

    public function destroy(SubjectArea $subjectArea): RedirectResponse
    {
        $subjectArea->delete();

        return redirect()->route('admin.subject-areas.index')->with('success', 'Subject area deleted successfully.');
    }

    private function validateData(Request $request, ?SubjectArea $subjectArea = null): array
    {
        return $request->validate([
            'key' => ['required', 'string', 'max:80', Rule::unique('subject_areas', 'key')->ignore($subjectArea?->id)],
            'name' => ['required', 'string', 'max:120'],
            'ar_name' => ['nullable', 'string', 'max:160'],
            'slug' => ['nullable', 'string', 'max:140', Rule::unique('subject_areas', 'slug')->ignore($subjectArea?->id)],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
