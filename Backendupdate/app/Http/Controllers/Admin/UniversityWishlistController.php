<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniversityWishlist;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UniversityWishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = UniversityWishlist::with(['user', 'course.courseCatalog', 'course.university'])->latest()->paginate(20);

        return view('admin.university-wishlists.index', compact('wishlists'));
    }

    public function destroy(UniversityWishlist $universityWishlist): RedirectResponse
    {
        $universityWishlist->delete();

        return redirect()->route('admin.university-wishlists.index')->with('success', 'Wishlist entry deleted successfully.');
    }
}
