<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseCompare;
use Illuminate\Http\Request;

class LanguageCourseCompareController extends Controller
{
    public function index()
    {
        $compares = LanguageCourseCompare::latest()->paginate(20);
        return view('admin.compares.index', compact('compares'));
    }

    public function destroy(LanguageCourseCompare $compare)
    {
        $compare->delete();
        return redirect()->route('admin.compares.index')->with('success', 'Compare item removed.');
    }
}
