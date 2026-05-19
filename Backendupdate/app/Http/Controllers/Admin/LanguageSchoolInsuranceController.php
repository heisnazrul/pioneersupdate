<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolInsurance;
use App\Models\LanguageSchoolBranch;
use Illuminate\Http\Request;

class LanguageSchoolInsuranceController extends Controller
{
    public function index()
    {
        $insurances = LanguageSchoolInsurance::with(['branch.school', 'branch.city'])->latest()->paginate(20);
        return view('admin.language-school-insurances.index', compact('insurances'));
    }

    public function create()
    {
        $branches = LanguageSchoolBranch::with('school')->get();
        return view('admin.language-school-insurances.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id'    => 'required|exists:language_school_branches,id|unique:language_school_insurances,branch_id',
            'weekly_fee'   => 'nullable|numeric|min:0',
            'admin_fee'    => 'nullable|numeric|min:0',
            'is_mandatory' => 'required|in:yes,no',
        ]);

        LanguageSchoolInsurance::create($data);
        return redirect()->route('admin.language-school-insurances.index')->with('success', 'Insurance settings added.');
    }

    public function edit(LanguageSchoolInsurance $languageSchoolInsurance)
    {
        $branches = LanguageSchoolBranch::with('school')->get();
        return view('admin.language-school-insurances.edit', compact('languageSchoolInsurance', 'branches'));
    }

    public function update(Request $request, LanguageSchoolInsurance $languageSchoolInsurance)
    {
        $data = $request->validate([
            'branch_id'    => 'required|exists:language_school_branches,id|unique:language_school_insurances,branch_id,' . $languageSchoolInsurance->id,
            'weekly_fee'   => 'nullable|numeric|min:0',
            'admin_fee'    => 'nullable|numeric|min:0',
            'is_mandatory' => 'required|in:yes,no',
        ]);

        $languageSchoolInsurance->update($data);
        return redirect()->route('admin.language-school-insurances.index')->with('success', 'Insurance settings updated.');
    }

    public function destroy(LanguageSchoolInsurance $languageSchoolInsurance)
    {
        $languageSchoolInsurance->delete();
        return redirect()->route('admin.language-school-insurances.index')->with('success', 'Insurance settings deleted.');
    }
}
