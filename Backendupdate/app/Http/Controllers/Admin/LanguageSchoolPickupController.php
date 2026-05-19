<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolPickup;
use App\Models\LanguageSchoolBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageSchoolPickupController extends Controller
{
    public function index(): View
    {
        $pickups = LanguageSchoolPickup::with(['branch.school', 'branch.city'])->latest()->paginate(20);
        return view('admin.language-school-pickups.index', compact('pickups'));
    }

    public function create(): View
    {
        $branches = LanguageSchoolBranch::with('school')->get();
        return view('admin.language-school-pickups.create', compact('branches'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'branch_id'       => 'required|exists:language_school_branches,id',
            'pickup_location' => 'required|string|max:255',
            'fee'             => 'required|numeric|min:0',
        ]);

        LanguageSchoolPickup::create($data);

        return redirect()->route('admin.language-school-pickups.index')
            ->with('success', 'Pickup location added successfully.');
    }

    public function edit(LanguageSchoolPickup $languageSchoolPickup): View
    {
        $branches = LanguageSchoolBranch::with('school')->get();
        return view('admin.language-school-pickups.edit', compact('languageSchoolPickup', 'branches'));
    }

    public function update(Request $request, LanguageSchoolPickup $languageSchoolPickup): RedirectResponse
    {
        $data = $request->validate([
            'branch_id'       => 'required|exists:language_school_branches,id',
            'pickup_location' => 'required|string|max:255',
            'fee'             => 'required|numeric|min:0',
        ]);

        $languageSchoolPickup->update($data);

        return redirect()->route('admin.language-school-pickups.index')
            ->with('success', 'Pickup location updated successfully.');
    }

    public function destroy(LanguageSchoolPickup $languageSchoolPickup): RedirectResponse
    {
        $languageSchoolPickup->delete();
        return redirect()->route('admin.language-school-pickups.index')
            ->with('success', 'Pickup location deleted.');
    }
}
