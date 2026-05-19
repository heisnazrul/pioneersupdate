<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseWishlist;
use Illuminate\Http\Request;

class LanguageCourseWishlistController extends Controller
{
    public function index()
    {
        $wishlists = LanguageCourseWishlist::latest()->paginate(20);
        return view('admin.wishlists.index', compact('wishlists'));
    }

    public function destroy(LanguageCourseWishlist $wishlist)
    {
        $wishlist->delete();
        return redirect()->route('admin.wishlists.index')->with('success', 'Wishlist item removed.');
    }
}
