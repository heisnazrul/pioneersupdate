<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScholarshipApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = ScholarshipApplication::with(['user', 'assignee']);

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('application_id', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('scholarship_title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.scholarship-applications.index', compact('applications'));
    }

    public function show(ScholarshipApplication $scholarshipApplication): View
    {
        return view('admin.scholarship-applications.show', compact('scholarshipApplication'));
    }

    public function edit(ScholarshipApplication $scholarshipApplication): View
    {
        $assignees = User::whereIn('role', ['admin', 'team', 'agent', 'uni_agent'])->orderBy('name')->get();

        return view('admin.scholarship-applications.edit', compact('scholarshipApplication', 'assignees'));
    }

    public function update(Request $request, ScholarshipApplication $scholarshipApplication): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,reviewing,approved,rejected'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        $scholarshipApplication->update($data);

        return redirect()->route('admin.scholarship-applications.edit', $scholarshipApplication)
            ->with('success', 'Scholarship application updated successfully.');
    }

    public function destroy(ScholarshipApplication $scholarshipApplication): RedirectResponse
    {
        $scholarshipApplication->delete();

        return redirect()->route('admin.scholarship-applications.index')->with('success', 'Scholarship application deleted successfully.');
    }
}
