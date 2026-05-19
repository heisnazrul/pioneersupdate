<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FeaturedListController extends Controller
{
    public function index(): View
    {
        $lists = FeaturedList::orderBy('name')->paginate(15);

        return view('admin.featured-lists.index', compact('lists'));
    }

    public function create(): View
    {
        return view('admin.featured-lists.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);

        FeaturedList::create($data);

        return redirect()->route('admin.featured-lists.index')->with('success', 'Featured list created successfully.');
    }

    public function edit(FeaturedList $featuredList): View
    {
        return view('admin.featured-lists.edit', compact('featuredList'));
    }

    public function update(Request $request, FeaturedList $featuredList): RedirectResponse
    {
        $data = $this->validateData($request, $featuredList);
        $data['is_active'] = $request->boolean('is_active');

        $featuredList->update($data);

        return redirect()->route('admin.featured-lists.index')->with('success', 'Featured list updated successfully.');
    }

    public function destroy(FeaturedList $featuredList): RedirectResponse
    {
        $featuredList->delete();

        return redirect()->route('admin.featured-lists.index')->with('success', 'Featured list deleted successfully.');
    }

    private function validateData(Request $request, ?FeaturedList $featuredList = null): array
    {
        return $request->validate([
            'key' => ['required', 'string', 'max:80', Rule::unique('featured_lists', 'key')->ignore($featuredList?->id)],
            'name' => ['required', 'string', 'max:120'],
            'ar_name' => ['nullable', 'string', 'max:150'],
        ]);
    }
}
