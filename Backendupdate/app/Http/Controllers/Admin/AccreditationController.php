<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccreditationController extends Controller
{
    public function index(): View
    {
        $accreditations = Accreditation::latest()->paginate(20);

        return view('admin.accreditations.index', compact('accreditations'));
    }

    public function create(): View
    {
        return view('admin.accreditations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        Accreditation::create($data);

        return redirect()->route('admin.accreditations.index')->with('success', 'Accreditation created.');
    }

    public function edit(Accreditation $accreditation): View
    {
        return view('admin.accreditations.edit', compact('accreditation'));
    }

    public function update(Request $request, Accreditation $accreditation): RedirectResponse
    {
        $data = $this->validatedData($request);

        $accreditation->update($data);

        return redirect()->route('admin.accreditations.index')->with('success', 'Accreditation updated.');
    }

    public function destroy(Accreditation $accreditation): RedirectResponse
    {
        $accreditation->delete();

        return redirect()->route('admin.accreditations.index')->with('success', 'Accreditation deleted.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
