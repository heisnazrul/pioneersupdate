<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\University;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UniversityController extends Controller
{
    public function index(Request $request): View
    {
        $query = University::query()->with(['country', 'city']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('country_id')) {
            $query->where('country_id', $request->input('country_id'));
        }

        $universities = $query->paginate(15)->withQueryString();
        $countries = Country::orderBy('name')->pluck('name', 'id');

        return view('admin.universities.index', compact('universities', 'countries'));
    }

    public function create(): View
    {
        $countries = Country::orderBy('name')->get();
        return view('admin.universities.create', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('universities/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('universities/covers', 'public');
        }

        University::create($data);

        return redirect()
            ->route('admin.universities.index')
            ->with('success', 'University created successfully.');
    }

    public function edit(University $university): View
    {
        $countries = Country::orderBy('name')->get();
        // Pre-load cities for the selected country (handled via JS in view usually, but passing all logic here or just rely on API/JS)
        // For simplicity in server-side render, we can pass cities of current country or all cities if handled by JS? 
        // Let's pass cities of the country to ensure edit works beautifully.
        $cities = City::where('country_id', $university->country_id)->orderBy('name')->get();

        return view('admin.universities.edit', compact('university', 'countries', 'cities'));
    }

    public function update(Request $request, University $university): RedirectResponse
    {
        $data = $this->validateData($request, $university);

        if ($request->hasFile('logo')) {
            $this->deleteFile($university->logo);
            $data['logo'] = $request->file('logo')->store('universities/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $this->deleteFile($university->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('universities/covers', 'public');
        }

        $university->update($data);

        return redirect()
            ->route('admin.universities.index')
            ->with('success', 'University updated successfully.');
    }

    public function destroy(University $university): RedirectResponse
    {
        $this->deleteFile($university->logo);
        $this->deleteFile($university->cover_image);
        $university->delete();

        return redirect()
            ->route('admin.universities.index')
            ->with('success', 'University deleted successfully.');
    }

    // Helper to get cities via AJAX if needed, or just standard validation
    public function getCities(Country $country)
    {
        return response()->json($country->cities()->orderBy('name')->select('id', 'name')->get());
    }

    private function validateData(Request $request, ?University $university = null): array
    {
        $ignoreId = $university?->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('universities', 'slug')->ignore($ignoreId)],
            'country_id' => ['required', Rule::exists('countries', 'id')],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'type' => ['required', 'in:public,private'],
            'website' => ['nullable', 'url', 'max:255'],
            'established_year' => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
            'qs_ranking' => ['nullable', 'integer', 'min:1'],
            'the_ranking' => ['nullable', 'integer', 'min:1'],
            'shanghai_ranking' => ['nullable', 'integer', 'min:1'],
            'famous_for' => ['nullable', 'string'],
            'ar_famous_for' => ['nullable', 'string'],
            'fees' => ['nullable', 'string'],
            'ar_fees' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_featured'] = $request->boolean('is_featured');

        return $data;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
