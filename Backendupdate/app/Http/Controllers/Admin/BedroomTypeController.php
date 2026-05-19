<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BedroomType;
use Illuminate\Http\Request;

class BedroomTypeController extends Controller
{
    public function index()
    {
        $types = BedroomType::latest()->paginate(20);
        return view('admin.bedroom-types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.bedroom-types.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
        ]);
        BedroomType::create($data);
        return redirect()->route('admin.bedroom-types.index')->with('success', 'Bedroom type created.');
    }

    public function edit(BedroomType $bedroomType)
    {
        return view('admin.bedroom-types.edit', compact('bedroomType'));
    }

    public function update(Request $request, BedroomType $bedroomType)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
        ]);
        $bedroomType->update($data);
        return redirect()->route('admin.bedroom-types.index')->with('success', 'Bedroom type updated.');
    }

    public function destroy(BedroomType $bedroomType)
    {
        $bedroomType->delete();
        return redirect()->route('admin.bedroom-types.index')->with('success', 'Bedroom type deleted.');
    }
}
