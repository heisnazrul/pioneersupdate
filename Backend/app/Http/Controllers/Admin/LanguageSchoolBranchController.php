<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Gallery;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageSchoolBranchController extends Controller
{
    public function index(): View
    {
        $filters = [
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'school_id' => request('school_id'),
        ];

        $query = LanguageSchoolBranch::with(['school', 'city.country']);

        if ($filters['country_id']) {
            $query->whereHas('city', fn ($q) => $q->where('country_id', $filters['country_id']));
        }
        if ($filters['city_id']) {
            $query->where('city_id', $filters['city_id']);
        }
        if ($filters['school_id']) {
            $query->where('language_school_id', $filters['school_id']);
        }

        $branches = $query->orderByDesc('created_at')->paginate(15)->withQueryString();

        $countries = Country::whereHas('cities.languageSchoolBranches')->orderBy('name')->get(['id', 'name']);
        $cities = City::whereHas('languageSchoolBranches')
            ->when($filters['country_id'], fn ($q) => $q->where('country_id', $filters['country_id']))
            ->orderBy('name')->get(['id', 'name']);
        $schools = LanguageSchool::whereHas('branches')
            ->when($filters['country_id'], fn ($q) => $q->whereHas('branches.city', fn ($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn ($q) => $q->whereHas('branches', fn ($qq) => $qq->where('city_id', $filters['city_id'])))
            ->orderBy('name')->get(['id', 'name']);

        return view('admin.language-school-branches.index', compact('branches', 'filters', 'countries', 'cities', 'schools'));
    }

    public function create(): View
    {
        return view('admin.language-school-branches.create', [
            'schools' => LanguageSchool::orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
            'galleryImages' => Gallery::where('use_case', 'branch_image')->orderBy('id')->get(['id', 'title']),
            'selectedGalleryImageIds' => [],
            'galleryText' => '',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        LanguageSchoolBranch::create($data);

        return redirect()->route('admin.language-school-branches.index')->with('success', 'Branch created successfully.');
    }

    public function edit(LanguageSchoolBranch $languageSchoolBranch): View
    {
        $galleryImages = Gallery::where('use_case', 'branch_image')->orderBy('id')->get(['id', 'title', 'image_path']);
        $pathToId = $galleryImages->pluck('id', 'image_path');
        $selectedGalleryImageIds = collect((array) $languageSchoolBranch->gallery_urls)
            ->map(function ($item) use ($pathToId) {
                if (is_numeric($item)) {
                    return (int) $item;
                }
                $value = trim((string) $item);
                return $pathToId[$value] ?? null;
            })
            ->filter()
            ->unique()
            ->values()
            ->all();

        return view('admin.language-school-branches.edit', [
            'branch' => $languageSchoolBranch,
            'schools' => LanguageSchool::orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
            'galleryImages' => $galleryImages,
            'selectedGalleryImageIds' => $selectedGalleryImageIds,
            'galleryText' => $languageSchoolBranch->gallery_urls ? implode("\n", (array) $languageSchoolBranch->gallery_urls) : '',
        ]);
    }

    public function update(Request $request, LanguageSchoolBranch $languageSchoolBranch): RedirectResponse
    {
        $data = $this->validateData($request, $languageSchoolBranch);
        $languageSchoolBranch->update($data);

        return redirect()->route('admin.language-school-branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(LanguageSchoolBranch $languageSchoolBranch): RedirectResponse
    {
        $languageSchoolBranch->delete();
        return redirect()->route('admin.language-school-branches.index')->with('success', 'Branch deleted successfully.');
    }

    private function validateData(Request $request, ?LanguageSchoolBranch $branch = null): array
    {
        $languageSchoolId = $request->input('language_school_id') ?: $request->input('school_id');

        $slugInput = $request->input('slug');
        if (blank($slugInput) && $languageSchoolId) {
            $slugInput = 'school-' . $languageSchoolId;
        }

        $request->merge([
            'language_school_id' => $languageSchoolId,
            'slug' => Str::slug($slugInput ?: ''),
        ]);

        $data = $request->validate([
            'language_school_id' => ['required', 'integer', 'exists:language_schools,id'],
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'slug' => ['required', 'string', 'max:160', Rule::unique('language_school_branches', 'slug')->ignore($branch?->id)],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'gallery_urls' => ['nullable', 'string'],
            'gallery_image_ids' => ['nullable', 'array'],
            'gallery_image_ids.*' => ['integer', 'exists:galleries,id'],
            'video_url' => ['nullable', 'string', 'max:255'],
        ], [], [
            'language_school_id' => 'school',
            'city_id' => 'city',
        ]);

        $manualUrls = collect(preg_split('/[\r\n,]+/', (string) ($data['gallery_urls'] ?? '')) ?: [])
            ->map(fn ($url) => trim($url))
            ->filter()
            ->values();

        $galleryPaths = collect();
        if (!empty($data['gallery_image_ids'])) {
            $galleryPaths = Gallery::query()
                ->whereIn('id', $data['gallery_image_ids'])
                ->pluck('image_path')
                ->map(fn ($path) => trim((string) $path))
                ->filter()
                ->values();
        }

        $data['gallery_urls'] = $galleryPaths
            ->merge($manualUrls)
            ->unique()
            ->values()
            ->all();

        unset($data['gallery_image_ids']);

        return $data;
    }
}
