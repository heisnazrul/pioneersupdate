<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolBranch;
use App\Models\LanguageSchoolBranchHighSeasonFee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageSchoolBranchHighSeasonFeeController extends Controller
{
    public function index(): View
    {
        $fees = LanguageSchoolBranchHighSeasonFee::with(['branch.school', 'branch.city'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.language-school-branch-high-season-fees.index', compact('fees'));
    }

    public function create(): View
    {
        $selectedBranchId = (int) request('branch_id', 0);
        $branches = $this->branchOptions();

        return view('admin.language-school-branch-high-season-fees.create', compact('branches', 'selectedBranchId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'week_start' => ['required', 'date'],
            'week_end' => ['nullable', 'date', 'after_or_equal:week_start'],
            'fee' => ['required', 'numeric', 'min:0'],
        ]);

        LanguageSchoolBranchHighSeasonFee::create($data);

        return redirect()->route('admin.language-school-branch-high-season-fees.index')->with('success', 'High season fee added.');
    }

    public function edit(LanguageSchoolBranchHighSeasonFee $ls_high_fee): View
    {
        $branches = $this->branchOptions();

        return view('admin.language-school-branch-high-season-fees.edit', [
            'fee' => $ls_high_fee,
            'branches' => $branches,
        ]);
    }

    public function update(Request $request, LanguageSchoolBranchHighSeasonFee $ls_high_fee): RedirectResponse
    {
        $data = $request->validate([
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'week_start' => ['required', 'date'],
            'week_end' => ['nullable', 'date', 'after_or_equal:week_start'],
            'fee' => ['required', 'numeric', 'min:0'],
        ]);

        $ls_high_fee->update($data);

        return redirect()->route('admin.language-school-branch-high-season-fees.index')->with('success', 'High season fee updated.');
    }

    public function destroy(LanguageSchoolBranchHighSeasonFee $ls_high_fee): RedirectResponse
    {
        $ls_high_fee->delete();
        return redirect()->route('admin.language-school-branch-high-season-fees.index')->with('success', 'High season fee deleted.');
    }

    private function branchOptions()
    {
        return LanguageSchoolBranch::query()
            ->with(['school:id,name', 'city:id,name'])
            ->withCount('highSeasonFees')
            ->withMax('highSeasonFees as high_season_fees_last_updated_at', 'updated_at')
            ->orderBy('slug')
            ->get();
    }
}
