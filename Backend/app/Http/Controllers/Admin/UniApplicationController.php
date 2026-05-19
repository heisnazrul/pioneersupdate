<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniApplication;

class UniApplicationController extends Controller
{
    public function index()
    {
        $applications = UniApplication::with('course')->latest()->paginate(10);
        return view('admin.uni_applications.index', compact('applications'));
    }
}
