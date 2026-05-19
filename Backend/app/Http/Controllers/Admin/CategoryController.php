<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::query()
            ->orderBy('display_order')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', [
            'category' => new BlogCategory(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        $data['slug'] = BlogCategory::generateSlug($data['slug'] ?? null, $data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['display_order'] = $data['display_order'] ?? 0;

        BlogCategory::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $category): View
    {
        return view('admin.categories.edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, BlogCategory $category): RedirectResponse
    {
        $data = $this->validateData($request, $category);

        $data['slug'] = BlogCategory::generateSlug($data['slug'] ?? null, $data['name'], $category->id);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['display_order'] = $data['display_order'] ?? 0;

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $category): RedirectResponse
    {
        if ($this->categoryInUse($category)) {
            return back()->withErrors([
                'category' => 'This category is attached to existing blog posts and cannot be deleted.',
            ]);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    private function validateData(Request $request, ?BlogCategory $category = null): array
    {
        $id = $category?->id;

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('blog_categories', 'slug')->ignore($id),
            ],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'color' => ['nullable', 'regex:/^#([0-9A-Fa-f]{6})$/'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function categoryInUse(BlogCategory $category): bool
    {
        if (!Schema::hasTable('blogs')) {
            return false;
        }

        return DB::table('blogs')
            ->where('category_id', $category->id)
            ->exists();
    }
}
