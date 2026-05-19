<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use App\Models\LanguageSchool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageSchoolController extends Controller
{
    public function index(): View
    {
        $schools = LanguageSchool::orderBy('name')->paginate(15)->withQueryString();
        return view('admin.language-schools.index', compact('schools'));
    }

    public function create(): View
    {
        return view('admin.language-schools.create', [
            'accreditations' => Accreditation::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('language-schools', 'public');
        }

        LanguageSchool::create($data);

        return redirect()->route('admin.language-schools.index')->with('success', 'Language school created successfully.');
    }

    public function edit(LanguageSchool $languageSchool): View
    {
        return view('admin.language-schools.edit', [
            'school' => $languageSchool,
            'accreditations' => Accreditation::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LanguageSchool $languageSchool): RedirectResponse
    {
        $data = $this->validateData($request, $languageSchool);

        if ($request->hasFile('logo')) {
            if ($languageSchool->logo) {
                Storage::disk('public')->delete($languageSchool->logo);
            }
            $data['logo'] = $request->file('logo')->store('language-schools', 'public');
        }

        $languageSchool->update($data);

        return redirect()->route('admin.language-schools.index')->with('success', 'Language school updated successfully.');
    }

    public function destroy(LanguageSchool $languageSchool): RedirectResponse
    {
        if ($languageSchool->logo) {
            Storage::disk('public')->delete($languageSchool->logo);
        }
        $languageSchool->delete();

        return redirect()->route('admin.language-schools.index')->with('success', 'Language school deleted successfully.');
    }

    private function validateData(Request $request, ?LanguageSchool $school = null): array
    {
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $request->input('name', '')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('language_schools', 'slug')->ignore($school?->id)],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:5120'],
            'accreditation_ids' => ['nullable', 'array'],
            'accreditation_ids.*' => ['integer', 'exists:accreditations,id'],
            'rating' => ['nullable', 'numeric', 'between:0,5'],
        ]);

        $data['accreditation_ids'] = array_map('intval', $data['accreditation_ids'] ?? []);
        $data['rating'] = $data['rating'] ?? 0;

        return $data;
    }
}
