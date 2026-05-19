<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->paginate(15);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.reviews.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ar_name' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048',
            'review_text' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video_iframe' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'university_name' => 'nullable|string|max:255',
            'course_name' => 'nullable|string|max:255',
            'country_name' => 'nullable|string|max:255',
            'institute_name' => 'nullable|string',
            'title' => 'nullable|string',
            'is_active' => 'boolean',
            'is_approved' => 'boolean', // Keeping backward compatibility
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('reviews/photos', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('reviews/thumbnails', 'public');
        }

        // Defaults
        $validated['is_active'] = $request->has('is_active');
        $validated['is_approved'] = $request->has('is_active'); // Sync approved with active for now

        Review::create($validated);

        return redirect()->route('admin.reviews.index')->with('success', 'Review created successfully.');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ar_name' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'photo' => 'nullable|image|max:2048',
            'review_text' => 'nullable|string',
            'video_url' => 'nullable|url',
            'video_iframe' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'university_name' => 'nullable|string|max:255',
            'course_name' => 'nullable|string|max:255',
            'country_name' => 'nullable|string|max:255',
            'institute_name' => 'nullable|string',
            'title' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('reviews/photos', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('reviews/thumbnails', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_approved'] = $request->has('is_active');

        $review->update($validated);

        return redirect()->route('admin.reviews.index')->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
