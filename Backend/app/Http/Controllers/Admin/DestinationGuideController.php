<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationGuide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DestinationGuideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guides = DestinationGuide::with('destination')->latest()->get();
        return view('admin.destination_guides.index', compact('guides'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();
        return view('admin.destination_guides.create', compact('destinations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'title' => 'required|string|max:255',
            'ar_title' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'is_active' => 'boolean',
        ]);

        $path = $request->file('file')->store('destination_guides', 'public');

        DestinationGuide::create([
            'destination_id' => $request->destination_id,
            'title' => $request->title,
            'ar_title' => $request->ar_title,
            'file_path' => $path,
            'year' => $request->year,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.destination-guides.index')->with('success', 'Destination Guide created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DestinationGuide $destinationGuide)
    {
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();
        return view('admin.destination_guides.edit', compact('destinationGuide', 'destinations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DestinationGuide $destinationGuide)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'title' => 'required|string|max:255',
            'ar_title' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:10240',
            'year' => 'required|integer|min:2020|max:' . (date('Y') + 1),
            'is_active' => 'boolean',
        ]);

        $data = [
            'destination_id' => $request->destination_id,
            'title' => $request->title,
            'ar_title' => $request->ar_title,
            'year' => $request->year,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('file')) {
            // Delete old file
            if ($destinationGuide->file_path) {
                Storage::disk('public')->delete($destinationGuide->file_path);
            }
            $data['file_path'] = $request->file('file')->store('destination_guides', 'public');
        }

        $destinationGuide->update($data);

        return redirect()->route('admin.destination-guides.index')->with('success', 'Destination Guide updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestinationGuide $destinationGuide)
    {
        if ($destinationGuide->file_path) {
            Storage::disk('public')->delete($destinationGuide->file_path);
        }
        $destinationGuide->delete();
        return redirect()->route('admin.destination-guides.index')->with('success', 'Destination Guide deleted successfully.');
    }
}
