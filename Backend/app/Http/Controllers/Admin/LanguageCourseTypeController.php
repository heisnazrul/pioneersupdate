<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageCourseTypeController extends Controller
{
    public function index(): View
    {
        $types = LanguageCourseType::query()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-course-types.index', [
            'types' => $types,
        ]);
    }

    public function create(): View
    {
        return view('admin.language-course-types.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        LanguageCourseType::create($data);

        return redirect()
            ->route('admin.language-course-types.index')
            ->with('success', 'Language course type created successfully.');
    }

    public function edit(LanguageCourseType $languageCourseType): View
    {
        return view('admin.language-course-types.edit', [
            'type' => $languageCourseType,
        ]);
    }

    public function update(Request $request, LanguageCourseType $languageCourseType): RedirectResponse
    {
        $data = $this->validateData($request, $languageCourseType);

        $languageCourseType->update($data);

        return redirect()
            ->route('admin.language-course-types.index')
            ->with('success', 'Language course type updated successfully.');
    }

    public function destroy(LanguageCourseType $languageCourseType): RedirectResponse
    {
        $languageCourseType->delete();

        return redirect()
            ->route('admin.language-course-types.index')
            ->with('success', 'Language course type deleted successfully.');
    }

    private function validateData(Request $request, ?LanguageCourseType $languageCourseType = null): array
    {
        return $request->validate([
            'type_code' => ['required', 'string', 'max:255', Rule::unique('language_course_types', 'type_code')->ignore($languageCourseType?->id)],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
        ]);
    }
}
