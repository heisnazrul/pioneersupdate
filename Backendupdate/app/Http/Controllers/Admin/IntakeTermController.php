<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeTerm;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class IntakeTermController extends Controller
{
    public function index(): View
    {
        $intakeTerms = IntakeTerm::orderBy('sort_order')->orderBy('month_num')->paginate(15);

        return view('admin.intake-terms.index', compact('intakeTerms'));
    }

    public function create(): View
    {
        return view('admin.intake-terms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);

        IntakeTerm::create($data);

        return redirect()->route('admin.intake-terms.index')->with('success', 'Intake term created successfully.');
    }

    public function edit(IntakeTerm $intakeTerm): View
    {
        return view('admin.intake-terms.edit', compact('intakeTerm'));
    }

    public function update(Request $request, IntakeTerm $intakeTerm): RedirectResponse
    {
        $data = $this->validateData($request, $intakeTerm);
        $data['is_active'] = $request->boolean('is_active');

        $intakeTerm->update($data);

        return redirect()->route('admin.intake-terms.index')->with('success', 'Intake term updated successfully.');
    }

    public function destroy(IntakeTerm $intakeTerm): RedirectResponse
    {
        $intakeTerm->delete();

        return redirect()->route('admin.intake-terms.index')->with('success', 'Intake term deleted successfully.');
    }

    private function validateData(Request $request, ?IntakeTerm $intakeTerm = null): array
    {
        return $request->validate([
            'key' => ['required', 'string', 'max:30', Rule::unique('intake_terms', 'key')->ignore($intakeTerm?->id)],
            'name' => ['required', 'string', 'max:50'],
            'ar_name' => ['nullable', 'string', 'max:80'],
            'month_num' => ['nullable', 'integer', 'min:1', 'max:12'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
