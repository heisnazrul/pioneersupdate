<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CityController extends Controller
{
    public function index(): View
    {
        $cities = City::with('country')->orderBy('display_order')->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.cities.index', compact('cities'));
    }

    public function create(): View
    {
        return view('admin.cities.create', ['countries' => Country::active()->orderBy('name')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        City::create($this->validateData($request));
        return redirect()->route('admin.cities.index')->with('success', 'City created successfully.');
    }

    public function edit(City $city): View
    {
        return view('admin.cities.edit', [
            'city'      => $city,
            'countries' => Country::active()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, City $city): RedirectResponse
    {
        $city->update($this->validateData($request, $city));
        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy(City $city): RedirectResponse
    {
        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully.');
    }

    private function validateData(Request $request, ?City $city = null): array
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'ar_name'       => ['required', 'string', 'max:255'],
            'slug'          => ['nullable', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'ar_description'=> ['nullable', 'string'],
            'country_id'    => ['required', Rule::exists('countries', 'id')],
            'latitude'      => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'     => ['nullable', 'numeric', 'between:-180,180'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['nullable', 'boolean'],
        ]);
        $data['is_active']    = $request->boolean('is_active', true);
        $data['display_order']= (int) ($request->input('display_order', 0) ?? 0);
        return $data;
    }
}
