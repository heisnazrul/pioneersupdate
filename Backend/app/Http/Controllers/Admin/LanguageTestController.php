<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageTest;
use Illuminate\Http\Request;

class LanguageTestController extends Controller
{
    public function index()
    {
        $languageTests = LanguageTest::orderBy('name')->paginate(10);
        return view('admin.language_tests.index', compact('languageTests'));
    }

    public function create()
    {
        return view('admin.language_tests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:30|unique:language_tests,key',
            'name' => 'required|string|max:60',
            'is_active' => 'boolean',
        ]);

        LanguageTest::create($validated);

        return redirect()->route('admin.language-tests.index')->with('success', 'Language Test created successfully.');
    }

    public function edit(LanguageTest $languageTest)
    {
        return view('admin.language_tests.edit', compact('languageTest'));
    }

    public function update(Request $request, LanguageTest $languageTest)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:30|unique:language_tests,key,' . $languageTest->id,
            'name' => 'required|string|max:60',
            'is_active' => 'boolean',
        ]);

        $languageTest->update($validated);

        return redirect()->route('admin.language-tests.index')->with('success', 'Language Test updated successfully.');
    }

    public function destroy(LanguageTest $languageTest)
    {
        $languageTest->delete();
        return redirect()->route('admin.language-tests.index')->with('success', 'Language Test deleted successfully.');
    }
}
