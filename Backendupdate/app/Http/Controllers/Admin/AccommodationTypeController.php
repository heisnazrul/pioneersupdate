<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccommodationType;
use Illuminate\Http\Request;

class AccommodationTypeController extends Controller
{
    public function index()
    {
        $types = AccommodationType::latest()->paginate(20);
        return view('admin.accommodation-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.accommodation-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
        ]);
        AccommodationType::create($data);
        return redirect()->route('admin.accommodation-types.index')->with('success', 'Type created.');
    }

    public function edit(AccommodationType $accommodationType)
    {
        return view('admin.accommodation-types.edit', compact('accommodationType'));
    }

    public function update(Request $request, AccommodationType $accommodationType)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
        ]);
        $accommodationType->update($data);
        return redirect()->route('admin.accommodation-types.index')->with('success', 'Type updated.');
    }

    public function destroy(AccommodationType $accommodationType)
    {
        $accommodationType->delete();
        return redirect()->route('admin.accommodation-types.index')->with('success', 'Type deleted.');
    }
}
