<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogTagController extends Controller
{
    public function index(): View
    {
        $tags = BlogTag::query()
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.blog-tags.index', [
            'tags' => $tags,
        ]);
    }

    public function create(): View
    {
        return view('admin.blog-tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->buildUniqueSlug($data['name']);

        BlogTag::create($data);

        return redirect()
            ->route('admin.blog-tags.index')
            ->with('success', 'Blog Tag created successfully.');
    }

    public function edit(BlogTag $blogTag): View
    {
        return view('admin.blog-tags.edit', [
            'tag' => $blogTag,
        ]);
    }

    public function update(Request $request, BlogTag $blogTag): RedirectResponse
    {
        $data = $this->validateData($request, $blogTag);
        $data['slug'] = $this->buildUniqueSlug($data['name'], $blogTag->id);

        $blogTag->update($data);

        return redirect()
            ->route('admin.blog-tags.index')
            ->with('success', 'Blog Tag updated successfully.');
    }

    public function destroy(BlogTag $blogTag): RedirectResponse
    {
        $blogTag->delete();

        return redirect()
            ->route('admin.blog-tags.index')
            ->with('success', 'Blog Tag deleted successfully.');
    }

    private function validateData(Request $request, ?BlogTag $tag = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{6})$/'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['display_order'] = $request->input('display_order', 0) ?? 0;

        return $data;
    }

    private function buildUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        if ($base === '') {
            $base = 'tag';
        }

        $slug = $base;
        $counter = 2;

        while (
            BlogTag::query()
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
