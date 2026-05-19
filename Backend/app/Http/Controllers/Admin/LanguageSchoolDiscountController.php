<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolDiscount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageSchoolDiscountController extends Controller
{
    public function index(): View
    {
        $discounts = LanguageSchoolDiscount::query()
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-school-discounts.index', [
            'discounts' => $discounts,
        ]);
    }

    public function create(): View
    {
        return view('admin.language-school-discounts.create', [
            'countries' => $this->getCountries(),
            'branches' => $this->getBranches(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        LanguageSchoolDiscount::create($data);

        return redirect()
            ->route('admin.language-school-discounts.index')
            ->with('success', 'Discount created successfully.');
    }

    public function edit(LanguageSchoolDiscount $ls_discount): View
    {
        return view('admin.language-school-discounts.edit', [
            'discount' => $ls_discount,
            'countries' => $this->getCountries(),
            'branches' => $this->getBranches(),
        ]);
    }

    public function update(Request $request, LanguageSchoolDiscount $ls_discount): RedirectResponse
    {
        $data = $this->validateData($request);
        $ls_discount->update($data);

        return redirect()
            ->route('admin.language-school-discounts.index')
            ->with('success', 'Discount updated successfully.');
    }

    public function destroy(LanguageSchoolDiscount $ls_discount): RedirectResponse
    {
        $ls_discount->delete();

        return redirect()
            ->route('admin.language-school-discounts.index')
            ->with('success', 'Discount deleted successfully.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'discount_percentage' => ['required', 'numeric', 'min:0', 'max:1000'],
            'applies_to_all_branches' => ['nullable', 'boolean'],
            'applies_to_all_countries' => ['nullable', 'boolean'],
            'school_branch_ids' => ['nullable', 'array'],
            'school_branch_ids.*' => ['integer', 'min:1'],
            'country_ids' => ['nullable', 'array'],
            'country_ids.*' => ['integer', 'min:1'],
            'applies_to_user_country' => ['nullable', 'boolean'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['applies_to_all_branches'] = $request->boolean('applies_to_all_branches', true);
        $data['applies_to_all_countries'] = $request->boolean('applies_to_all_countries', true);
        $data['applies_to_user_country'] = $request->boolean('applies_to_user_country', false);
        $data['is_active'] = $request->boolean('is_active', true);

        $data['school_branch_ids'] = $this->normalizeIds($request->input('school_branch_ids'));
        $data['country_ids'] = $this->normalizeIds($request->input('country_ids'));

        return $data;
    }

    private function normalizeIds($raw): ?array
    {
        if ($raw === null) {
            return null;
        }

        $ids = array_map('intval', array_filter((array) $raw, fn ($v) => $v !== null && $v !== ''));

        return $ids === [] ? null : $ids;
    }

    private function getCountries()
    {
        return Country::query()
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    private function getBranches()
    {
        return LanguageSchoolBranch::query()
            ->with(['school', 'city'])
            ->orderBy('id')
            ->get(['id', 'language_school_id', 'city_id', 'slug']);
    }
}
