<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $applications = Application::query()
            ->with(['user', 'assignedUser'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.applications.index', [
            'applications' => $applications,
            'statuses'     => Application::STATUSES,
        ]);
    }

    public function show(Application $application): View
    {
        return view('admin.applications.show', [
            'application' => $application->load(['user', 'assignedUser']),
            'statuses'    => Application::STATUSES,
            'counsellors' => User::whereIn('role', ['counsellor', 'team', 'admin'])->orderBy('name')->get(),
        ]);
    }

    public function edit(Application $application): View
    {
        return view('admin.applications.edit', [
            'application' => $application,
            'statuses'    => Application::STATUSES,
            'counsellors' => User::whereIn('role', ['counsellor', 'team', 'admin'])->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Application $application): RedirectResponse
    {
        $data = $request->validate([
            'status'       => ['required', Rule::in(Application::STATUSES)],
            'assigned_to'  => ['nullable', Rule::exists('users', 'id')],
            'assigned_role'=> ['nullable', 'string', 'max:100'],
            'status_notes' => ['nullable', 'string'],
        ]);

        $application->update($data);
        return redirect()->route('admin.applications.show', $application)->with('success', 'Application updated.');
    }

    public function destroy(Application $application): RedirectResponse
    {
        $application->delete();
        return redirect()->route('admin.applications.index')->with('success', 'Application deleted.');
    }
}
