<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $items = Gallery::query()
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.gallery.index', [
            'items' => $items,
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        Gallery::create($data);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Image uploaded successfully.');
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.gallery.edit', [
            'gallery' => $gallery,
        ]);
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $data = $this->validateData($request, false);

        if ($request->hasFile('image')) {
            $newPath = $request->file('image')->store('gallery', 'public');
            $this->deleteImage($gallery->image_path);
            $data['image_path'] = $newPath;
        }

        $gallery->update($data);

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Image updated successfully.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $this->deleteImage($gallery->image_path);
        $gallery->delete();

        return redirect()
            ->route('admin.gallery.index')
            ->with('success', 'Image deleted successfully.');
    }

    private function validateData(Request $request, bool $isCreate): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'use_case' => ['nullable', 'string', 'max:100'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'image' => [$isCreate ? 'required' : 'nullable', 'image', 'max:5120'],
        ]);
    }

    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
