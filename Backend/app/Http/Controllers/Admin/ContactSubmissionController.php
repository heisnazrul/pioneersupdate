<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactSubmission;

class ContactSubmissionController extends Controller
{
    public function index()
    {
        $submissions = ContactSubmission::latest()->paginate(10);
        return view('admin.contact_submissions.index', compact('submissions'));
    }

    public function update(Request $request, $id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function destroy($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->delete();

        return redirect()->back()->with('success', 'Submission deleted successfully.');
    }
}
