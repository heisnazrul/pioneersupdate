<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Application::with('assignee');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_id', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(15);
        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $application = Application::findOrFail($id);
        return view('admin.applications.show', compact('application'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $application = Application::findOrFail($id);
        // ideally filter users by role 'admin' or 'councillor'
        $staff = User::whereIn('role', ['admin', 'councillor', 'team', 'agent'])->get();

        return view('admin.applications.edit', compact('application', 'staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $request->validate([
            'status' => ['required', Rule::in(['draft','pending','submitted','reviewing','contacted','accepted','rejected','invalid'])],
            'assigned_to' => 'nullable|exists:users,id',
            'status_notes' => 'nullable|string'
        ]);

        $application->status = $request->status;
        $application->assigned_to = $request->assigned_to;
        $application->status_notes = $request->status_notes;

        // Auto-assign role based on assignee
        if ($request->assigned_to) {
            $assignee = User::find($request->assigned_to);
            $application->assigned_role = $assignee->role;
        }

        $application->save();

        return redirect()->route('admin.applications.index')
            ->with('success', 'Application updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();
        return redirect()->route('admin.applications.index')
            ->with('success', 'Application deleted successfully');
    }
}
