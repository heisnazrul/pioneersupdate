<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogTagController extends Controller
{
    public function index(): View
    {
        $tags = BlogTag::orderBy('display_order')->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.blog-tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('admin.blog-tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        BlogTag::create($this->validateData($request));
        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag created successfully.');
    }

    public function edit(BlogTag $blogTag): View
    {
        return view('admin.blog-tags.edit', ['tag' => $blogTag]);
    }

    public function update(Request $request, BlogTag $blogTag): RedirectResponse
    {
        $blogTag->update($this->validateData($request, $blogTag));
        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag updated successfully.');
    }

    public function destroy(BlogTag $blogTag): RedirectResponse
    {
        $blogTag->delete();
        return redirect()->route('admin.blog-tags.index')->with('success', 'Blog tag deleted successfully.');
    }

    private function validateData(Request $request, ?BlogTag $tag = null): array
    {
        $id   = $tag?->id;
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'ar_name'       => ['nullable', 'string', 'max:255'],
            'slug'          => ['nullable', 'string', Rule::unique('blog_tags', 'slug')->ignore($id)],
            'description'   => ['nullable', 'string'],
            'ar_description'=> ['nullable', 'string'],
            'color'         => ['nullable', 'string', 'max:9'],
            'display_order' => ['nullable', 'integer'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $base = Str::slug($data['name']);
            $candidate = $base; $i = 1;
            while (BlogTag::where('slug', $candidate)->when($id, fn($q) => $q->where('id', '!=', $id))->exists()) {
                $candidate = "{$base}-{$i}"; $i++;
            }
            $data['slug'] = $candidate;
        }

        $data['is_active']    = $request->boolean('is_active', true);
        $data['display_order']= (int) ($request->input('display_order', 0) ?? 0);
        return $data;
    }
}
