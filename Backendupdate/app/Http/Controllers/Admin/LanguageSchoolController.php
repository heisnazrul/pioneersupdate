<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LanguageSchoolController extends Controller
{
    public function index(): View
    {
        $schools = LanguageSchool::orderBy('name_en')->paginate(20);
        return view('admin.language-schools.index', compact('schools'));
    }

    public function create(): View
    {
        $logos = \App\Models\Gallery::where('use_case', 'school_logo')->get();
        return view('admin.language-schools.create', compact('logos'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_en'      => 'required|string|max:255',
            'name_ar'      => 'nullable|string|max:255',
            'slug'         => 'nullable|string|max:255|unique:language_schools,slug',
            'gallery_logo' => 'nullable|string',
            'has_online'   => 'required|in:yes,no',
            'status'       => 'required|in:active,inactive',
        ]);

        if (!$request->filled('slug')) {
            $data['slug'] = Str::slug($data['name_en']);
        }

        $data['logo_url'] = $request->gallery_logo;

        LanguageSchool::create($data);

        return redirect()->route('admin.language-schools.index')
            ->with('success', 'Language School created successfully.');
    }

    public function edit(LanguageSchool $languageSchool): View
    {
        $logos = \App\Models\Gallery::where('use_case', 'school_logo')->get();
        return view('admin.language-schools.edit', compact('languageSchool', 'logos'));
    }

    public function update(Request $request, LanguageSchool $languageSchool): RedirectResponse
    {
        $data = $request->validate([
            'name_en'      => 'required|string|max:255',
            'name_ar'      => 'nullable|string|max:255',
            'slug'         => 'required|string|max:255|unique:language_schools,slug,' . $languageSchool->id,
            'gallery_logo' => 'nullable|string',
            'has_online'   => 'required|in:yes,no',
            'status'       => 'required|in:active,inactive',
        ]);

        $data['logo_url'] = $request->gallery_logo;

        $languageSchool->update($data);

        return redirect()->route('admin.language-schools.index')
            ->with('success', 'Language School updated successfully.');
    }

    public function destroy(LanguageSchool $languageSchool): RedirectResponse
    {
        if ($languageSchool->logo_url) {
            Storage::disk('public')->delete($languageSchool->logo_url);
        }
        $languageSchool->delete();

        return redirect()->route('admin.language-schools.index')
            ->with('success', 'Language School deleted successfully.');
    }
}
