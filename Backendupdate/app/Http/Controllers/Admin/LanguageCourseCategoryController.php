<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LanguageCourseCategoryController extends Controller
{
    public function index(): View
    {
        $categories = LanguageCourseCategory::latest()->paginate(20);
        return view('admin.language-course-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.language-course-categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_en'   => 'required|string|max:255',
            'name_ar'   => 'nullable|string|max:255',
            'slug'      => 'nullable|string|max:255|unique:language_course_categories,slug',
            'is_active' => 'required|in:yes,no',
        ]);

        if (!$request->filled('slug')) {
            $data['slug'] = Str::slug($data['name_en']);
        }

        LanguageCourseCategory::create($data);

        return redirect()->route('admin.language-course-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(LanguageCourseCategory $languageCourseCategory): View
    {
        return view('admin.language-course-categories.edit', compact('languageCourseCategory'));
    }

    public function update(Request $request, LanguageCourseCategory $languageCourseCategory): RedirectResponse
    {
        $data = $request->validate([
            'name_en'   => 'required|string|max:255',
            'name_ar'   => 'nullable|string|max:255',
            'slug'      => 'required|string|max:255|unique:language_course_categories,slug,' . $languageCourseCategory->id,
            'is_active' => 'required|in:yes,no',
        ]);

        $languageCourseCategory->update($data);

        return redirect()->route('admin.language-course-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(LanguageCourseCategory $languageCourseCategory): RedirectResponse
    {
        $languageCourseCategory->delete();
        return redirect()->route('admin.language-course-categories.index')
            ->with('success', 'Category deleted.');
    }
}
