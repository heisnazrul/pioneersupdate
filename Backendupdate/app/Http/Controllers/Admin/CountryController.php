<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\City;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(): View
    {
        $countries = Country::orderBy('display_order')->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.countries.index', compact('countries'));
    }

    public function create(): View
    {
        return view('admin.countries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        unset($data['flag']);

        if ($request->hasFile('flag')) {
            $data['flag'] = $request->file('flag')->store('flags', 'public');
        }

        Country::create($data);
        return redirect()->route('admin.countries.index')->with('success', 'Country created successfully.');
    }

    public function edit(Country $country): View
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $data = $this->validateData($request, $country);
        unset($data['flag']);

        if ($request->boolean('remove_flag')) {
            if ($country->flag) Storage::disk('public')->delete($country->flag);
            $data['flag'] = null;
        } elseif ($request->hasFile('flag')) {
            if ($country->flag) Storage::disk('public')->delete($country->flag);
            $data['flag'] = $request->file('flag')->store('flags', 'public');
        }

        $country->update($data);
        return redirect()->route('admin.countries.index')->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        if ($country->cities()->count()) {
            return back()->withErrors(['country' => 'Cannot delete country with existing cities.']);
        }
        if ($country->flag) Storage::disk('public')->delete($country->flag);
        $country->delete();
        return redirect()->route('admin.countries.index')->with('success', 'Country deleted successfully.');
    }

    private function validateData(Request $request, ?Country $country = null): array
    {
        $id   = $country?->id;
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'ar_name'            => ['required', 'string', 'max:255'],
            'auxiliary_name'     => ['nullable', 'string', 'max:255'],
            'ar_auxiliary_name'  => ['nullable', 'string', 'max:255'],
            'slug'               => ['nullable', 'string', 'max:255', Rule::unique('countries', 'slug')->ignore($id)],
            'country_code'       => ['required', 'string', 'max:10', Rule::unique('countries', 'country_code')->ignore($id)],
            'currency_code'=> ['required', 'string', 'max:10'],
            'phone_code'   => ['nullable', 'string', 'max:10'],
            'capital'      => ['nullable', 'string', 'max:255'],
            'continent'    => ['nullable', 'string', 'max:100'],
            'description'  => ['nullable', 'string'],
            'ar_description'=> ['nullable', 'string'],
            'flag'         => ['nullable', 'image:allow_svg', 'max:2048'],
            'is_popular'   => ['nullable', 'boolean'],
            'is_active'    => ['nullable', 'boolean'],
            'display_order'=> ['nullable', 'integer', 'min:0'],
        ]);

        if (!isset($data['slug']) || !$data['slug']) {
            $base = Str::slug($data['name']);
            $candidate = $base; $i = 1;
            while (Country::where('slug', $candidate)->when($id, fn($q) => $q->where('id', '!=', $id))->exists()) {
                $candidate = "{$base}-{$i}"; $i++;
            }
            $data['slug'] = $candidate;
        }

        $data['is_popular']   = $request->boolean('is_popular');
        $data['is_active']    = $request->boolean('is_active', true);
        $data['display_order']= (int) ($request->input('display_order', 0) ?? 0);
        return $data;
    }
}
