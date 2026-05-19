<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BathroomType;
use Illuminate\Http\Request;

class BathroomTypeController extends Controller
{
    public function index()
    {
        $types = BathroomType::latest()->paginate(20);
        return view('admin.bathroom-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.bathroom-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
        ]);
        BathroomType::create($data);
        return redirect()->route('admin.bathroom-types.index')->with('success', 'Bathroom type created.');
    }

    public function edit(BathroomType $bathroomType)
    {
        return view('admin.bathroom-types.edit', compact('bathroomType'));
    }

    public function update(Request $request, BathroomType $bathroomType)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
        ]);
        $bathroomType->update($data);
        return redirect()->route('admin.bathroom-types.index')->with('success', 'Bathroom type updated.');
    }

    public function destroy(BathroomType $bathroomType)
    {
        $bathroomType->delete();
        return redirect()->route('admin.bathroom-types.index')->with('success', 'Bathroom type deleted.');
    }
}
