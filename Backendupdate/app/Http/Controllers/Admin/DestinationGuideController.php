<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\DestinationGuide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DestinationGuideController extends Controller
{
    public function index(): View
    {
        $guides = DestinationGuide::with('destination')->latest()->paginate(15);

        return view('admin.destination-guides.index', compact('guides'));
    }

    public function create(): View
    {
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();

        return view('admin.destination-guides.create', compact('destinations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true);
        $data['file_path'] = $request->file('file')->store('destination_guides', 'public');
        $data['is_active'] = $request->boolean('is_active', true);

        DestinationGuide::create($data);

        return redirect()->route('admin.destination-guides.index')->with('success', 'Destination guide created successfully.');
    }

    public function edit(DestinationGuide $destinationGuide): View
    {
        $destinations = Destination::where('is_active', true)->orderBy('name')->get();

        return view('admin.destination-guides.edit', compact('destinationGuide', 'destinations'));
    }

    public function update(Request $request, DestinationGuide $destinationGuide): RedirectResponse
    {
        $data = $this->validateData($request, false);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('file')) {
            if ($destinationGuide->file_path) {
                Storage::disk('public')->delete($destinationGuide->file_path);
            }

            $data['file_path'] = $request->file('file')->store('destination_guides', 'public');
        }

        $destinationGuide->update($data);

        return redirect()->route('admin.destination-guides.index')->with('success', 'Destination guide updated successfully.');
    }

    public function destroy(DestinationGuide $destinationGuide): RedirectResponse
    {
        if ($destinationGuide->file_path) {
            Storage::disk('public')->delete($destinationGuide->file_path);
        }

        $destinationGuide->delete();

        return redirect()->route('admin.destination-guides.index')->with('success', 'Destination guide deleted successfully.');
    }

    private function validateData(Request $request, bool $fileRequired): array
    {
        return $request->validate([
            'destination_id' => ['required', 'exists:destinations,id'],
            'title' => ['required', 'string', 'max:255'],
            'ar_title' => ['nullable', 'string', 'max:255'],
            'file' => [$fileRequired ? 'required' : 'nullable', 'file', 'mimes:pdf', 'max:10240'],
            'year' => ['nullable', 'integer', 'min:2020', 'max:' . (now()->year + 1)],
        ]);
    }
}
