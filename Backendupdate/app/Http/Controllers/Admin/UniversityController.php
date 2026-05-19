<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\University;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UniversityController extends Controller
{
    public function index(Request $request): View
    {
        $query = University::with(['country', 'city']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->string('search') . '%');
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->integer('country_id'));
        }

        $universities = $query->latest()->paginate(15)->withQueryString();
        $countries = Country::active()->orderBy('name')->get();
        $logoImages = \App\Models\Gallery::where('use_case', 'university_logo')->get();
        $coverImages = \App\Models\Gallery::where('use_case', 'university_cover')->get();

        return view('admin.universities.index', compact('universities', 'countries', 'logoImages', 'coverImages'));
    }

    public function create(): View
    {
        $countries = Country::active()->orderBy('name')->get();
        $cities = City::active()->orderBy('name')->get();

        return view('admin.universities.create', compact('countries', 'cities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['logo'] = $request->input('gallery_logo');
        $data['cover_image'] = $request->input('gallery_cover_image');
        unset($data['gallery_logo'], $data['gallery_cover_image']);

        University::create($data);

        return redirect()->route('admin.universities.index')->with('success', 'University created successfully.');
    }

    public function edit(University $university): View
    {
        $countries = Country::active()->orderBy('name')->get();
        $cities = City::active()->orderBy('name')->get();

        return view('admin.universities.edit', compact('university', 'countries', 'cities'));
    }

    public function update(Request $request, University $university): RedirectResponse
    {
        $data = $this->validateData($request, $university);
        $data['is_active'] = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['logo'] = $request->input('gallery_logo');
        $data['cover_image'] = $request->input('gallery_cover_image');
        unset($data['gallery_logo'], $data['gallery_cover_image']);

        $university->update($data);

        return redirect()->route('admin.universities.index')->with('success', 'University updated successfully.');
    }

    public function destroy(University $university): RedirectResponse
    {
        $university->delete();

        return redirect()->route('admin.universities.index')->with('success', 'University deleted successfully.');
    }

    private function validateData(Request $request, ?University $university = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('universities', 'slug')->ignore($university?->id)],
            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'type' => ['required', Rule::in(['public', 'private'])],
            'established_year' => ['nullable', 'integer', 'min:1000', 'max:' . now()->year],
            'website' => ['nullable', 'url', 'max:255'],
            'qs_ranking' => ['nullable', 'integer', 'min:1'],
            'the_ranking' => ['nullable', 'integer', 'min:1'],
            'shanghai_ranking' => ['nullable', 'integer', 'min:1'],
            'famous_for' => ['nullable', 'string'],
            'ar_famous_for' => ['nullable', 'string'],
            'fees' => ['nullable', 'string'],
            'ar_fees' => ['nullable', 'string'],
            'gallery_logo' => ['nullable', 'string', 'max:255'],
            'gallery_cover_image' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
