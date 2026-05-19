<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactSubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $submissions = ContactSubmission::query()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.contact-submissions.index', [
            'submissions' => $submissions,
            'statuses'    => ContactSubmission::STATUSES,
        ]);
    }

    public function show(ContactSubmission $contactSubmission): View
    {
        return view('admin.contact-submissions.show', ['submission' => $contactSubmission]);
    }

    public function update(Request $request, ContactSubmission $contactSubmission): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(ContactSubmission::STATUSES)],
        ]);
        $contactSubmission->update($data);
        return back()->with('success', 'Status updated.');
    }

    public function destroy(ContactSubmission $contactSubmission): RedirectResponse
    {
        $contactSubmission->delete();
        return redirect()->route('admin.contact-submissions.index')->with('success', 'Submission deleted.');
    }
}
