<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function create(): View
    {
        return view('admin.reviews.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('reviews', 'public');
        }
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('reviews', 'public');
        }
        Review::create($data);
        return redirect()->route('admin.reviews.index')->with('success', 'Review created.');
    }

    public function edit(Review $review): View
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $data = $this->validateData($request);
        if ($request->hasFile('photo')) {
            if ($review->photo) Storage::disk('public')->delete($review->photo);
            $data['photo'] = $request->file('photo')->store('reviews', 'public');
        }
        if ($request->hasFile('thumbnail')) {
            if ($review->thumbnail) Storage::disk('public')->delete($review->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('reviews', 'public');
        }
        $review->update($data);
        return redirect()->route('admin.reviews.index')->with('success', 'Review updated.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        if ($review->photo) Storage::disk('public')->delete($review->photo);
        if ($review->thumbnail) Storage::disk('public')->delete($review->thumbnail);
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }

    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'name'            => ['required', 'string', 'max:255'],
            'ar_name'         => ['nullable', 'string', 'max:255'],
            'photo'           => ['nullable', 'image', 'max:3072'],
            'institute_name'  => ['nullable', 'string', 'max:255'],
            'ar_institute_name'=> ['nullable', 'string', 'max:255'],
            'title'           => ['nullable', 'string', 'max:255'],
            'ar_title'        => ['nullable', 'string', 'max:255'],
            'review_text'     => ['nullable', 'string'],
            'ar_review_text'  => ['nullable', 'string'],
            'gender'          => ['nullable', 'string', 'in:male,female'],
            'rating'          => ['required', 'integer', 'min:1', 'max:5'],
            'university_name' => ['nullable', 'string', 'max:255'],
            'course_name'     => ['nullable', 'string', 'max:255'],
            'country_name'    => ['nullable', 'string', 'max:255'],
            'video_url'       => ['nullable', 'url'],
            'video_iframe'    => ['nullable', 'string'],
            'thumbnail'       => ['nullable', 'image', 'max:3072'],
            'is_approved'     => ['nullable', 'boolean'],
            'is_active'       => ['nullable', 'boolean'],
        ]);
        $data['is_approved'] = $request->boolean('is_approved');
        $data['is_active']   = $request->boolean('is_active');
        return $data;
    }
}
