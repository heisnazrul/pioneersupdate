<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccommodationRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AccommodationRoomController extends Controller
{
    public function index()
    {
        $rooms = UniversityAccommodationRoom::all();
        return view('admin.accommodation_rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.accommodation_rooms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ar_title' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:university_accommodation_rooms',
            'description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'price' => 'nullable|string',
            'features' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'details' => 'nullable|string',
            'ar_details' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('accommodation', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        UniversityAccommodationRoom::create($validated);

        return redirect()->route('admin.accommodation-rooms.index')->with('success', 'Room created successfully.');
    }

    public function edit(UniversityAccommodationRoom $accommodationRoom)
    {
        return view('admin.accommodation_rooms.edit', compact('accommodationRoom'));
    }

    public function update(Request $request, UniversityAccommodationRoom $accommodationRoom)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ar_title' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:university_accommodation_rooms,slug,' . $accommodationRoom->id,
            'description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'price' => 'nullable|string',
            'features' => 'nullable|array',
            'image' => 'nullable|image|max:2048',
            'details' => 'nullable|string',
            'ar_details' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($accommodationRoom->image) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $accommodationRoom->image));
            }
            $path = $request->file('image')->store('accommodation', 'public');
            $validated['image'] = '/storage/' . $path;
        }

        $accommodationRoom->update($validated);

        return redirect()->route('admin.accommodation-rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(UniversityAccommodationRoom $accommodationRoom)
    {
        if ($accommodationRoom->image) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $accommodationRoom->image));
        }
        $accommodationRoom->delete();

        return redirect()->route('admin.accommodation-rooms.index')->with('success', 'Room deleted successfully.');
    }
}
