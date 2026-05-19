<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolInsuranceFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageSchoolInsuranceFeeController extends Controller
{
    public function index(): View
    {
        $filters = [
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'school_id' => request('school_id'),
            'branch_id' => request('branch_id'),
            'billing_unit' => request('billing_unit'),
        ];

        $items = LanguageSchoolInsuranceFee::with('branch.school')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branch.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branch', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->when($filters['school_id'], fn($q) => $q->whereHas('branch', fn($qq) => $qq->where('language_school_id', $filters['school_id'])))
            ->when($filters['branch_id'], fn($q) => $q->where('branch_id', $filters['branch_id']))
            ->when($filters['billing_unit'], fn($q) => $q->where('billing_unit', $filters['billing_unit']))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $countries = \App\Models\Country::whereHas('cities.languageSchoolBranches.insuranceFees')->orderBy('name')->get(['id','name']);
        $cities = \App\Models\City::whereHas('languageSchoolBranches.insuranceFees')
            ->when($filters['country_id'], fn($q) => $q->where('country_id', $filters['country_id']))
            ->orderBy('name')->get(['id','name','country_id']);
        $schools = \App\Models\LanguageSchool::whereHas('branches.insuranceFees')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branches.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branches', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->orderBy('name')->get();
        $branches = \App\Models\LanguageSchoolBranch::whereHas('insuranceFees')
            ->when($filters['school_id'], fn($q) => $q->where('language_school_id', $filters['school_id']))
            ->when($filters['city_id'], fn($q) => $q->where('city_id', $filters['city_id']))
            ->when($filters['country_id'], fn($q) => $q->whereHas('city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->with('school')
            ->orderBy('slug')
            ->get();

        return view('admin.language-school-insurance-fees.index', [
            'insurances' => $items,
            'filters' => $filters,
            'countries' => $countries,
            'cities' => $cities,
            'schools' => $schools,
            'branches' => $branches,
        ]);
    }

    public function create(): View
    {
        $branches = LanguageSchoolBranch::with('school')->orderBy('slug')->get();
        return view('admin.language-school-insurance-fees.create', compact('branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        LanguageSchoolInsuranceFee::create($data);

        return redirect()->route('admin.language-school-insurance-fees.index')->with('success', 'Insurance fee created.');
    }

    public function edit(LanguageSchoolInsuranceFee $languageSchoolInsuranceFee): View
    {
        $branches = LanguageSchoolBranch::with('school')->orderBy('slug')->get();
        return view('admin.language-school-insurance-fees.edit', [
            'insuranceFee' => $languageSchoolInsuranceFee,
            'branches' => $branches,
        ]);
    }

    public function update(Request $request, LanguageSchoolInsuranceFee $languageSchoolInsuranceFee): RedirectResponse
    {
        $data = $this->validateData($request);
        $languageSchoolInsuranceFee->update($data);

        return redirect()->route('admin.language-school-insurance-fees.index')->with('success', 'Insurance fee updated.');
    }

    public function destroy(LanguageSchoolInsuranceFee $languageSchoolInsuranceFee): RedirectResponse
    {
        $languageSchoolInsuranceFee->delete();
        return redirect()->route('admin.language-school-insurance-fees.index')->with('success', 'Insurance fee deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'admin_charge' => ['nullable', 'numeric', 'min:0'],
            'billing_unit' => ['required', Rule::in(['week', 'month', 'course'])],
            'billing_count' => ['required', 'integer', 'min:1'],
            'valid_from' => ['nullable', 'date'],
            'valid_to' => ['nullable', 'date', 'after_or_equal:valid_from'],
        ]);
    }
}
