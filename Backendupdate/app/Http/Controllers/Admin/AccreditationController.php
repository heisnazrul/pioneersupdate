<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accreditation;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    public function index()
    {
        $accreditations = Accreditation::latest()->paginate(20);
        return view('admin.accreditations.index', compact('accreditations'));
    }

    public function create()
    {
        return view('admin.accreditations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
        ]);
        Accreditation::create($data);
        return redirect()->route('admin.accreditations.index')->with('success', 'Accreditation created.');
    }

    public function edit(Accreditation $accreditation)
    {
        return view('admin.accreditations.edit', compact('accreditation'));
    }

    public function update(Request $request, Accreditation $accreditation)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'logo' => 'nullable|string|max:255',
        ]);
        $accreditation->update($data);
        return redirect()->route('admin.accreditations.index')->with('success', 'Accreditation updated.');
    }

    public function destroy(Accreditation $accreditation)
    {
        $accreditation->delete();
        return redirect()->route('admin.accreditations.index')->with('success', 'Accreditation deleted.');
    }
}
