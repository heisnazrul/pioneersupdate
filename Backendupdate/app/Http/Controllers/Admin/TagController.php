<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::query()
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Tag::create($this->validatedData($request));

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $tag->update($this->validatedData($request, $tag));

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }

    private function validatedData(Request $request, ?Tag $tag = null): array
    {
        $id = $tag?->id;
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tags', 'slug')->ignore($id)],
        ]);

        if (empty($data['slug'])) {
            $base = Str::slug($data['name']);
            $candidate = $base;
            $index = 1;

            while (Tag::query()
                ->where('slug', $candidate)
                ->when($id, fn ($query) => $query->where('id', '!=', $id))
                ->exists()) {
                $candidate = "{$base}-{$index}";
                $index++;
            }

            $data['slug'] = $candidate;
        }

        return $data;
    }
}
