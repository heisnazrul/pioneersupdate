<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolSupplement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageSchoolSupplementController extends Controller
{
    public function index(): View
    {
        $filters = [
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'school_id' => request('school_id'),
            'branch_id' => request('branch_id'),
        ];

        $items = LanguageSchoolSupplement::with('branch.school')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branch.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branch', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->when($filters['school_id'], fn($q) => $q->whereHas('branch', fn($qq) => $qq->where('language_school_id', $filters['school_id'])))
            ->when($filters['branch_id'], fn($q) => $q->where('branch_id', $filters['branch_id']))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $countries = \App\Models\Country::whereHas('cities.languageSchoolBranches.supplements')->orderBy('name')->get(['id','name']);
        $cities = \App\Models\City::whereHas('languageSchoolBranches.supplements')
            ->when($filters['country_id'], fn($q) => $q->where('country_id', $filters['country_id']))
            ->orderBy('name')->get(['id','name','country_id']);
        $schools = \App\Models\LanguageSchool::whereHas('branches.supplements')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branches.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branches', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->orderBy('name')->get();
        $branches = \App\Models\LanguageSchoolBranch::whereHas('supplements')
            ->when($filters['school_id'], fn($q) => $q->where('language_school_id', $filters['school_id']))
            ->when($filters['city_id'], fn($q) => $q->where('city_id', $filters['city_id']))
            ->when($filters['country_id'], fn($q) => $q->whereHas('city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->with('school')
            ->orderBy('slug')
            ->get();

        return view('admin.language-school-supplements.index', [
            'supplements' => $items,
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
        return view('admin.language-school-supplements.create', compact('branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        LanguageSchoolSupplement::create($data);

        return redirect()->route('admin.language-school-supplements.index')->with('success', 'Supplement created.');
    }

    public function edit(LanguageSchoolSupplement $languageSchoolSupplement): View
    {
        $branches = LanguageSchoolBranch::with('school')->orderBy('slug')->get();
        return view('admin.language-school-supplements.edit', [
            'supplement' => $languageSchoolSupplement,
            'branches' => $branches,
        ]);
    }

    public function update(Request $request, LanguageSchoolSupplement $languageSchoolSupplement): RedirectResponse
    {
        $data = $this->validateData($request);
        $languageSchoolSupplement->update($data);

        return redirect()->route('admin.language-school-supplements.index')->with('success', 'Supplement updated.');
    }

    public function destroy(LanguageSchoolSupplement $languageSchoolSupplement): RedirectResponse
    {
        $languageSchoolSupplement->delete();
        return redirect()->route('admin.language-school-supplements.index')->with('success', 'Supplement deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);
    }
}
