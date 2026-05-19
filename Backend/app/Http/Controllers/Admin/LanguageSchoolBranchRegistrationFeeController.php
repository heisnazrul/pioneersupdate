<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchool;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolBranchRegistrationFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageSchoolBranchRegistrationFeeController extends Controller
{
    public function index(): View
    {
        $filters = [
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'school_id' => request('school_id'),
            'branch_id' => request('branch_id'),
        ];

        $fees = LanguageSchoolBranchRegistrationFee::with('branch.school')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branch.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branch', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->when($filters['school_id'], fn($q) => $q->whereHas('branch', fn($qq) => $qq->where('language_school_id', $filters['school_id'])))
            ->when($filters['branch_id'], fn($q) => $q->where('branch_id', $filters['branch_id']))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $countries = \App\Models\Country::whereHas('cities.languageSchoolBranches.registrationFees')->orderBy('name')->get(['id','name']);
        $cities = \App\Models\City::whereHas('languageSchoolBranches.registrationFees')
            ->when($filters['country_id'], fn($q) => $q->where('country_id', $filters['country_id']))
            ->orderBy('name')->get(['id','name','country_id']);
        $schools = LanguageSchool::whereHas('branches.registrationFees')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branches.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branches', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->orderBy('name')->get();
        $branches = LanguageSchoolBranch::whereHas('registrationFees')
            ->when($filters['school_id'], fn($q) => $q->where('language_school_id', $filters['school_id']))
            ->when($filters['city_id'], fn($q) => $q->where('city_id', $filters['city_id']))
            ->when($filters['country_id'], fn($q) => $q->whereHas('city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->with('school')
            ->orderBy('slug')
            ->get();

        return view('admin.language-school-branch-registration-fees.index', compact('fees', 'branches', 'schools', 'countries', 'cities', 'filters'));
    }

    public function create(): View
    {
        $branches = LanguageSchoolBranch::with('school')->orderBy('slug')->get();
        return view('admin.language-school-branch-registration-fees.create', compact('branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        LanguageSchoolBranchRegistrationFee::create($data);

        return redirect()->route('admin.language-school-branch-registration-fees.index')->with('success', 'Registration fee added.');
    }

    public function edit(LanguageSchoolBranchRegistrationFee $languageSchoolBranchRegistrationFee): View
    {
        $branches = LanguageSchoolBranch::with('school')->orderBy('slug')->get();
        return view('admin.language-school-branch-registration-fees.edit', [
            'fee' => $languageSchoolBranchRegistrationFee,
            'branches' => $branches,
        ]);
    }

    public function update(Request $request, LanguageSchoolBranchRegistrationFee $languageSchoolBranchRegistrationFee): RedirectResponse
    {
        $data = $request->validate([
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $languageSchoolBranchRegistrationFee->update($data);

        return redirect()->route('admin.language-school-branch-registration-fees.index')->with('success', 'Registration fee updated.');
    }

    public function destroy(LanguageSchoolBranchRegistrationFee $languageSchoolBranchRegistrationFee): RedirectResponse
    {
        $languageSchoolBranchRegistrationFee->delete();
        return redirect()->route('admin.language-school-branch-registration-fees.index')->with('success', 'Registration fee deleted.');
    }
}
