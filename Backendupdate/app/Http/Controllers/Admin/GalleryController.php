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
    public function index(Request $request): View
    {
        $galleries = Gallery::query()
            ->when($request->use_case, fn($q) => $q->where('use_case', $request->use_case))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $useCases = Gallery::distinct()->pluck('use_case')->filter()->values();

        return view('admin.galleries.index', compact('galleries', 'useCases'));
    }

    public function create(): View
    {
        $useCases = Gallery::distinct()->pluck('use_case')->filter()->values();
        return view('admin.galleries.create', compact('useCases'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'    => ['nullable', 'string', 'max:255'],
            'use_case' => ['nullable', 'string', 'max:100'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'images'   => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'max:5120'],
        ]);

        foreach ($request->file('images') as $image) {
            Gallery::create([
                'title'      => $data['title'] ?? null,
                'use_case'   => $data['use_case'] ?? null,
                'alt_text'   => $data['alt_text'] ?? null,
                'image_path' => $image->store('gallery', 'public'),
            ]);
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Images uploaded successfully.');
    }

    public function search(Request $request)
    {
        $query = Gallery::query()
            ->when($request->use_case, fn($q) => $q->where('use_case', $request->use_case))
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->orderByDesc('created_at')
            ->get();

        return response()->json($query->map(fn($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'path' => $item->image_path,
            'url' => Storage::url($item->image_path),
        ]));
    }

    public function apiStore(Request $request)
    {
        $request->validate([
            'title'    => ['nullable', 'string', 'max:255'],
            'use_case' => ['required', 'string', 'max:100'],
            'image'    => ['required', 'image', 'max:5120'],
        ]);

        $item = Gallery::create([
            'title'      => $request->title,
            'use_case'   => $request->use_case,
            'image_path' => $request->file('image')->store('gallery', 'public'),
        ]);

        return response()->json([
            'id' => $item->id,
            'title' => $item->title,
            'path' => $item->image_path,
            'url' => Storage::url($item->image_path),
        ]);
    }
}
