<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeTerm;
use Illuminate\Http\Request;

class IntakeTermController extends Controller
{
    public function index()
    {
        $intakeTerms = IntakeTerm::orderBy('sort_order')->orderBy('month_num')->paginate(10);
        return view('admin.intake_terms.index', compact('intakeTerms'));
    }

    public function create()
    {
        return view('admin.intake_terms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:30|unique:intake_terms,key',
            'name' => 'required|string|max:50',
            'month_num' => 'nullable|integer|min:1|max:12',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        IntakeTerm::create($validated);

        return redirect()->route('admin.intake-terms.index')->with('success', 'Intake Term created successfully.');
    }

    public function edit(IntakeTerm $intakeTerm)
    {
        return view('admin.intake_terms.edit', compact('intakeTerm'));
    }

    public function update(Request $request, IntakeTerm $intakeTerm)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:30|unique:intake_terms,key,' . $intakeTerm->id,
            'name' => 'required|string|max:50',
            'month_num' => 'nullable|integer|min:1|max:12',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $intakeTerm->update($validated);

        return redirect()->route('admin.intake-terms.index')->with('success', 'Intake Term updated successfully.');
    }

    public function destroy(IntakeTerm $intakeTerm)
    {
        $intakeTerm->delete();
        return redirect()->route('admin.intake-terms.index')->with('success', 'Intake Term deleted successfully.');
    }
}
