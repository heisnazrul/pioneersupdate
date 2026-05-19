<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(): View
    {
        $countries = Country::query()
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.countries.index', [
            'countries' => $countries,
        ]);
    }

    public function create(): View
    {
        return view('admin.countries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('flag_upload')) {
            $data['flag'] = $request->file('flag_upload')->store('flags', 'public');
        }

        Country::create($data);

        return redirect()
            ->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    public function edit(Country $country): View
    {
        return view('admin.countries.edit', [
            'country' => $country,
        ]);
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $data = $this->validateData($request, $country);

        if ($request->boolean('remove_flag')) {
            $this->deleteFlag($country->flag);
            $data['flag'] = null; // Explicitly null
        }

        if ($request->hasFile('flag_upload')) {
            // Delete old flag if exists and not just replaced (though good practice to delete)
            $this->deleteFlag($country->flag);
            $data['flag'] = $request->file('flag_upload')->store('flags', 'public');
        }

        $country->update($data);

        return redirect()
            ->route('admin.countries.index')
            ->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        $this->deleteFlag($country->flag);
        $country->delete();

        return redirect()
            ->route('admin.countries.index')
            ->with('success', 'Country deleted successfully.');
    }

    private function validateData(Request $request, ?Country $country = null): array
    {
        $ignoreId = $country?->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('countries', 'slug')->ignore($ignoreId)],
            'flag' => ['nullable', 'string', 'max:255'], // URL or path if manual input allowed, though logic prefers upload
            'flag_upload' => ['nullable', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'country_code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('countries', 'country_code')->ignore($ignoreId),
            ],
            'currency_code' => ['required', 'string', 'max:10'],
            'phone_code' => ['nullable', 'string', 'max:10'], // Legacy was required string, migration is nullable
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'capital' => ['nullable', 'string', 'max:255'],
            'continent' => ['nullable', 'string', 'max:255'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_popular' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['is_popular'] = $request->boolean('is_popular');

        // Remove 'flag' input field from data if we are relying on upload, 
        // OR handle it if we want to allow manual path entry.
        // Legacy code: $data['flag'] = $this->defaultFlagValue($data['flag'] ?? $country->flag ?? null);
        // My simplified approach:
        if (!isset($data['flag']) && isset($country->flag)) {
            // Keep existing if not touched, but update() handles this by not overwriting unless in array
        }

        return $data;
    }

    private function deleteFlag(?string $flag): void
    {
        if (!$flag) {
            return;
        }

        if (Storage::disk('public')->exists($flag)) {
            Storage::disk('public')->delete($flag);
        }
    }
}
