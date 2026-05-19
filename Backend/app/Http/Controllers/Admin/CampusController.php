<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\University;
use App\Models\UniversityCampus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $campuses = UniversityCampus::with(['university', 'city'])->orderBy('university_id')->paginate(10);
        return view('admin.campuses.index', compact('campuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $universities = University::orderBy('name')->get();
        $cities = City::with('country')->orderBy('name')->get();
        return view('admin.campuses.create', compact('universities', 'cities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:150',
            'ar_name' => 'nullable|string|max:200',
            'is_online' => 'boolean',
            'city_id' => [
                'nullable',
                'exists:cities,id',
                Rule::requiredIf(fn() => !$request->boolean('is_online'))
            ],
            'address' => 'nullable|string',
            'ar_address' => 'nullable|string',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        $slug = Str::slug($validated['name']);

        // Ensure unique slug per university
        $count = UniversityCampus::where('university_id', $validated['university_id'])
            ->where('slug', $slug)
            ->count();

        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $validated['slug'] = $slug;

        UniversityCampus::create($validated);

        return redirect()->route('admin.campuses.index')->with('success', 'Campus created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UniversityCampus $campus)
    {
        $universities = University::orderBy('name')->get();
        $cities = City::with('country')->orderBy('name')->get();
        return view('admin.campuses.edit', compact('campus', 'universities', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UniversityCampus $campus)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:150',
            'ar_name' => 'nullable|string|max:200',
            'is_online' => 'boolean',
            'city_id' => [
                'nullable',
                'exists:cities,id',
                Rule::requiredIf(fn() => !$request->boolean('is_online'))
            ],
            'address' => 'nullable|string',
            'ar_address' => 'nullable|string',
            'lat' => 'nullable|numeric|between:-90,90',
            'lng' => 'nullable|numeric|between:-180,180',
            'is_active' => 'boolean',
        ]);

        if ($campus->name !== $validated['name']) {
            $slug = Str::slug($validated['name']);
            // Check uniqueness excluding current
            $count = UniversityCampus::where('university_id', $validated['university_id'])
                ->where('slug', $slug)
                ->where('id', '!=', $campus->id)
                ->count();

            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            $validated['slug'] = $slug;
        }

        $campus->update($validated);

        return redirect()->route('admin.campuses.index')->with('success', 'Campus updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UniversityCampus $campus)
    {
        $campus->delete();

        return redirect()->route('admin.campuses.index')->with('success', 'Campus deleted successfully.');
    }
}
