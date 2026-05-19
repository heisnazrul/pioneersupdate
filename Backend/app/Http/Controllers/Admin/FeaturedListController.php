<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedList;
use Illuminate\Http\Request;

class FeaturedListController extends Controller
{
    public function index()
    {
        $lists = FeaturedList::orderBy('name')->paginate(15);
        return view('admin.featured_lists.index', compact('lists'));
    }

    public function create()
    {
        return view('admin.featured_lists.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:80|unique:featured_lists,key',
            'name' => 'required|string|max:120',
            'ar_name' => 'nullable|string|max:150',
            'is_active' => 'boolean',
        ]);

        FeaturedList::create($validated);

        return redirect()->route('admin.featured-lists.index')->with('success', 'Featured list created.');
    }

    public function edit(FeaturedList $featured_list)
    {
        return view('admin.featured_lists.edit', ['featuredList' => $featured_list]);
    }

    public function update(Request $request, FeaturedList $featured_list)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:80|unique:featured_lists,key,' . $featured_list->id,
            'name' => 'required|string|max:120',
            'ar_name' => 'nullable|string|max:150',
            'is_active' => 'boolean',
        ]);

        $featured_list->update($validated);

        return redirect()->route('admin.featured-lists.index')->with('success', 'Featured list updated.');
    }

    public function destroy(FeaturedList $featured_list)
    {
        $featured_list->delete();
        return redirect()->route('admin.featured-lists.index')->with('success', 'Featured list deleted.');
    }
}
