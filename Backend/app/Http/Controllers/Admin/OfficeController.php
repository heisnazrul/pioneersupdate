<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::latest()->get();
        return view('admin.offices.index', compact('offices'));
    }

    public function create()
    {
        return view('admin.offices.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'ar_city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'ar_country' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:offices,slug',
            'type' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'ar_address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'map_url' => 'nullable|url',
            'description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'hours' => 'nullable|string|max:255',
            'ar_hours' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('offices', 'public');
        }

        Office::create($validated);

        return redirect()->route('admin.offices.index')->with('success', 'Office created successfully.');
    }

    public function edit(Office $office)
    {
        return view('admin.offices.edit', compact('office'));
    }

    public function update(Request $request, Office $office)
    {
        $validated = $request->validate([
            'city' => 'required|string|max:255',
            'ar_city' => 'nullable|string|max:255',
            'country' => 'required|string|max:255',
            'ar_country' => 'nullable|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('offices', 'slug')->ignore($office->id)],
            'type' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'ar_address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'map_url' => 'nullable|url',
            'description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'hours' => 'nullable|string|max:255',
            'ar_hours' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('offices', 'public');
        }

        $office->update($validated);

        return redirect()->route('admin.offices.index')->with('success', 'Office updated successfully.');
    }

    public function destroy(Office $office)
    {
        $office->delete();
        return redirect()->route('admin.offices.index')->with('success', 'Office deleted successfully.');
    }
}
