<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DestinationController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('country')->paginate(10);
        return view('admin.destinations.index', compact('destinations'));
    }

    public function create()
    {
        $countries = Country::active()->get();
        return view('admin.destinations.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Handle Image Upload
        if ($request->hasFile('image_url')) {
            $data['image_url'] = $request->file('image_url')->store('destinations/images', 'public');
        }

        $destination = Destination::create($data);

        // Handle Relations (Features, Stats, etc. - Basic implementation for now, expandable later)
        // For MVP/First Pass, we focus on the main fields. 
        // Logic for nested arrays (features, stats) can be added or handled via separate tabs/methods if complex.

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully.');
    }

    public function edit(Destination $destination)
    {
        $countries = Country::active()->get();
        return view('admin.destinations.edit', compact('destination', 'countries'));
    }

    public function update(Request $request, Destination $destination)
    {
        $data = $this->validateData($request, $destination->id);

        if ($request->hasFile('image_url')) {
            if ($destination->image_url) {
                Storage::disk('public')->delete($destination->image_url);
            }
            $data['image_url'] = $request->file('image_url')->store('destinations/images', 'public');
        }

        $destination->update($data);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination)
    {
        $destination->delete();
        return redirect()->route('admin.destinations.index')->with('success', 'Destination deleted successfully.');
    }

    protected function validateData(Request $request, $id = null)
    {
        return $request->validate([
            'country_id' => 'nullable|exists:countries,id',
            'name' => 'required|string|max:255',
            'ar_name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255|unique:destinations,slug,' . $id,
            'region' => 'nullable|string|max:255',
            'ar_region' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'image_url' => 'nullable|image|max:2048', // 2MB Max
            'short_pitch' => 'nullable|string',
            'ar_short_pitch' => 'nullable|string',
            'tuition_range' => 'nullable|string|max:255',
            'ar_tuition_range' => 'nullable|string|max:255',
            'visa_timeline' => 'nullable|string|max:255',
            'ar_visa_timeline' => 'nullable|string|max:255',
            'work_rights' => 'nullable|string|max:255',
            'ar_work_rights' => 'nullable|string|max:255',
            'scholarships_summary' => 'nullable|string|max:255',
            'ar_scholarships_summary' => 'nullable|string|max:255',
            'entry_req_gpa' => 'nullable|string',
            'ar_entry_req_gpa' => 'nullable|string',
            'entry_req_language' => 'nullable|string',
            'ar_entry_req_language' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
    }
}
