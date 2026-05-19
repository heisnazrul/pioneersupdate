<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccommodationRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UniversityAccommodationRoomController extends Controller
{
    public function index(): View
    {
        $rooms = UniversityAccommodationRoom::latest()->paginate(15);

        return view('admin.university-accommodation-rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        return view('admin.university-accommodation-rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['image'] = $request->input('gallery_image');
        $data['features'] = $this->parseFeatures($request->input('features_text'));
        unset($data['gallery_image'], $data['features_text']);

        UniversityAccommodationRoom::create($data);

        return redirect()->route('admin.university-accommodation-rooms.index')->with('success', 'Accommodation room created successfully.');
    }

    public function edit(UniversityAccommodationRoom $universityAccommodationRoom): View
    {
        return view('admin.university-accommodation-rooms.edit', compact('universityAccommodationRoom'));
    }

    public function update(Request $request, UniversityAccommodationRoom $universityAccommodationRoom): RedirectResponse
    {
        $data = $this->validateData($request, $universityAccommodationRoom);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['image'] = $request->input('gallery_image');
        $data['features'] = $this->parseFeatures($request->input('features_text'));
        unset($data['gallery_image'], $data['features_text']);

        $universityAccommodationRoom->update($data);

        return redirect()->route('admin.university-accommodation-rooms.index')->with('success', 'Accommodation room updated successfully.');
    }

    public function destroy(UniversityAccommodationRoom $universityAccommodationRoom): RedirectResponse
    {
        $universityAccommodationRoom->delete();

        return redirect()->route('admin.university-accommodation-rooms.index')->with('success', 'Accommodation room deleted successfully.');
    }

    private function validateData(Request $request, ?UniversityAccommodationRoom $room = null): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'ar_title' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('university_accommodation_rooms', 'slug')->ignore($room?->id)],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'price' => ['nullable', 'string', 'max:255'],
            'gallery_image' => ['nullable', 'string', 'max:255'],
            'features_text' => ['nullable', 'string'],
            'details' => ['nullable', 'string'],
            'ar_details' => ['nullable', 'string'],
        ]);
    }

    private function parseFeatures(?string $value): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }
}
