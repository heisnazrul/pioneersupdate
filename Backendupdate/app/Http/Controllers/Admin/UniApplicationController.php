<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UniApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UniApplicationController extends Controller
{
    public function index(): View
    {
        $applications = UniApplication::with('course.courseCatalog', 'course.university')->latest()->paginate(15);

        return view('admin.uni-applications.index', compact('applications'));
    }

    public function edit(UniApplication $uniApplication): View
    {
        return view('admin.uni-applications.edit', compact('uniApplication'));
    }

    public function update(Request $request, UniApplication $uniApplication): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'intake' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:100'],
        ]);

        $uniApplication->update($data);

        return redirect()->route('admin.uni-applications.edit', $uniApplication)->with('success', 'University application updated successfully.');
    }

    public function destroy(UniApplication $uniApplication): RedirectResponse
    {
        $uniApplication->delete();

        return redirect()->route('admin.uni-applications.index')->with('success', 'University application deleted successfully.');
    }
}
