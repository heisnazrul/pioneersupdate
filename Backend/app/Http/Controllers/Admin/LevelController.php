<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::orderBy('sort_order')->orderBy('name')->paginate(10);
        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        return view('admin.levels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:50|unique:levels,key',
            'name' => 'required|string|max:80',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        Level::create($validated);

        return redirect()->route('admin.levels.index')->with('success', 'Level created successfully.');
    }

    public function edit(Level $level)
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:50|unique:levels,key,' . $level->id,
            'name' => 'required|string|max:80',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $level->update($validated);

        return redirect()->route('admin.levels.index')->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return redirect()->route('admin.levels.index')->with('success', 'Level deleted successfully.');
    }
}
