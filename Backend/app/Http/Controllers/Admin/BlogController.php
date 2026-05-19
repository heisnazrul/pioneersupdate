<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::query()
            ->with(['category', 'publisher'])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.blogs.index', [
            'blogs' => $blogs,
        ]);
    }

    public function create(): View
    {
        return view('admin.blogs.create', $this->formData());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        $data['publisher_id'] = auth()->id(); // Assign current user as publisher

        $blog = Blog::create($data);
        $blog->tags()->sync($request->tags ?? []);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog created successfully.');
    }

    public function edit(Blog $blog): View
    {
        return view('admin.blogs.edit', $this->formData($blog));
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $data = $this->validateRequest($request, $blog);

        if ($request->boolean('remove_featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = null;
        } elseif ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        $blog->update($data);
        $blog->tags()->sync($request->tags ?? []);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog deleted successfully.');
    }

    private function validateRequest(Request $request, ?Blog $blog = null): array
    {
        $id = $blog?->id;

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'ar_title' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('blogs', 'slug')->ignore($id),
            ],
            'summary' => ['nullable', 'string'],
            'ar_summary' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'ar_content' => ['nullable', 'string'],
            'category_id' => ['required', Rule::exists('blog_categories', 'id')->whereNull('deleted_at')],
            'audience_scope' => ['required', Rule::in(Blog::AUDIENCE_OPTIONS)],
            'featured_image' => ['nullable', 'image', 'max:5120'],
            'published_at' => ['nullable', 'date'],
            'remove_featured_image' => ['sometimes', 'boolean'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:blog_tags,id'],
        ]);

        $data['slug'] = Blog::generateSlug($data['slug'] ?? null, $data['title'], $id);

        return $data;
    }

    private function formData(?Blog $blog = null): array
    {
        return [
            'blog' => $blog,
            'categories' => BlogCategory::query()->active()->orderBy('display_order')->orderBy('name')->get(),
            'blogTags' => BlogTag::query()->active()->orderBy('display_order')->orderBy('name')->get(),
            'audienceScopes' => Blog::AUDIENCE_OPTIONS,
        ];
    }
}
