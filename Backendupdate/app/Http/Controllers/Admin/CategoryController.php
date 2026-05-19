<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = BlogCategory::orderBy('display_order')->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        BlogCategory::create($this->validateData($request));
        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, BlogCategory $category): RedirectResponse
    {
        $category->update($this->validateData($request, $category));
        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }

    private function validateData(Request $request, ?BlogCategory $category = null): array
    {
        $id   = $category?->id;
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'ar_name'       => ['nullable', 'string', 'max:255'],
            'slug'          => ['nullable', 'string', 'max:255', Rule::unique('blog_categories', 'slug')->ignore($id)->whereNull('deleted_at')],
            'description'   => ['nullable', 'string'],
            'ar_description'=> ['nullable', 'string'],
            'color'         => ['nullable', 'string', 'max:9'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active'     => ['nullable', 'boolean'],
        ]);

        if (empty($data['slug'])) {
            $base = Str::slug($data['name']);
            $candidate = $base; $i = 1;
            while (BlogCategory::withTrashed()->where('slug', $candidate)->when($id, fn($q) => $q->where('id', '!=', $id))->exists()) {
                $candidate = "{$base}-{$i}"; $i++;
            }
            $data['slug'] = $candidate;
        }

        $data['is_active']    = $request->boolean('is_active', true);
        $data['display_order']= (int) ($request->input('display_order', 0) ?? 0);
        return $data;
    }
}
