<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageCourseTagController extends Controller
{
    public function index(): View
    {
        $tags = LanguageCourseTag::query()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-course-tags.index', [
            'tags' => $tags,
        ]);
    }

    public function create(): View
    {
        return view('admin.language-course-tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        LanguageCourseTag::create($data);

        return redirect()
            ->route('admin.language-course-tags.index')
            ->with('success', 'Language course tag created successfully.');
    }

    public function edit(LanguageCourseTag $languageCourseTag): View
    {
        return view('admin.language-course-tags.edit', [
            'tag' => $languageCourseTag,
        ]);
    }

    public function update(Request $request, LanguageCourseTag $languageCourseTag): RedirectResponse
    {
        $data = $this->validateData($request, $languageCourseTag);

        $languageCourseTag->update($data);

        return redirect()
            ->route('admin.language-course-tags.index')
            ->with('success', 'Language course tag updated successfully.');
    }

    public function destroy(LanguageCourseTag $languageCourseTag): RedirectResponse
    {
        $languageCourseTag->delete();

        return redirect()
            ->route('admin.language-course-tags.index')
            ->with('success', 'Language course tag deleted successfully.');
    }

    private function validateData(Request $request, ?LanguageCourseTag $tag = null): array
    {
        return $request->validate([
            'tag_code' => ['required', 'string', 'max:255', Rule::unique('language_course_tags', 'tag_code')->ignore($tag?->id)],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
        ]);
    }
}
