<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use App\Models\User;
use Illuminate\Http\Request;

class ScholarshipApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = ScholarshipApplication::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('application_id', 'like', "%{$search}%")
                    ->orWhere('scholarship_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.scholarship_applications.index', compact('applications'));
    }

    public function show(ScholarshipApplication $scholarshipApplication)
    {
        return view('admin.scholarship_applications.show', compact('scholarshipApplication'));
    }

    public function edit(ScholarshipApplication $scholarshipApplication)
    {
        $assignees = User::where('role', 'admin')->orWhere('role', 'agent')->orderBy('name')->get();
        return view('admin.scholarship_applications.edit', compact('scholarshipApplication', 'assignees'));
    }

    public function update(Request $request, ScholarshipApplication $scholarshipApplication)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewing,approved,rejected',
            'assignee_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $scholarshipApplication->update($validated);

        return back()->with('success', 'Application updated successfully');
    }

    public function destroy(ScholarshipApplication $scholarshipApplication)
    {
        $scholarshipApplication->delete();
        return back()->with('success', 'Application deleted successfully');
    }
}
