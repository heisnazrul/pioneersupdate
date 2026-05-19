<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LevelController extends Controller
{
    public function index(): View
    {
        $levels = Level::orderBy('sort_order')->orderBy('name')->paginate(15);

        return view('admin.levels.index', compact('levels'));
    }

    public function create(): View
    {
        return view('admin.levels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);

        Level::create($data);

        return redirect()->route('admin.levels.index')->with('success', 'Level created successfully.');
    }

    public function edit(Level $level): View
    {
        return view('admin.levels.edit', compact('level'));
    }

    public function update(Request $request, Level $level): RedirectResponse
    {
        $data = $this->validateData($request, $level);
        $data['is_active'] = $request->boolean('is_active');

        $level->update($data);

        return redirect()->route('admin.levels.index')->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level): RedirectResponse
    {
        $level->delete();

        return redirect()->route('admin.levels.index')->with('success', 'Level deleted successfully.');
    }

    private function validateData(Request $request, ?Level $level = null): array
    {
        return $request->validate([
            'key' => ['required', 'string', 'max:50', Rule::unique('levels', 'key')->ignore($level?->id)],
            'name' => ['required', 'string', 'max:80'],
            'ar_name' => ['nullable', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer'],
        ]);
    }
}
