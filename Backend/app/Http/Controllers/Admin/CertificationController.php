<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use Illuminate\Http\Request;

class CertificationController extends Controller
{
    public function index()
    {
        $certifications = Certification::latest()->paginate(15);
        return view('admin.certifications.index', compact('certifications'));
    }

    public function create()
    {
        return view('admin.certifications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ar_title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'ar_subtitle' => 'nullable|string|max:255',
            'certificate_image' => 'nullable|image|max:2048',
            'certification_link' => 'nullable|url',
        ]);

        if ($request->hasFile('certificate_image')) {
            $validated['certificate_image'] = $request->file('certificate_image')->store('certifications', 'public');
        }

        Certification::create($validated);

        return redirect()->route('admin.certifications.index')->with('success', 'Certification created successfully.');
    }

    public function edit(Certification $certification)
    {
        return view('admin.certifications.edit', compact('certification'));
    }

    public function update(Request $request, Certification $certification)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ar_title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'ar_subtitle' => 'nullable|string|max:255',
            'certificate_image' => 'nullable|image|max:2048',
            'certification_link' => 'nullable|url',
        ]);

        if ($request->hasFile('certificate_image')) {
            $validated['certificate_image'] = $request->file('certificate_image')->store('certifications', 'public');
        }

        $certification->update($validated);

        return redirect()->route('admin.certifications.index')->with('success', 'Certification updated successfully.');
    }

    public function destroy(Certification $certification)
    {
        $certification->delete();
        return redirect()->route('admin.certifications.index')->with('success', 'Certification deleted successfully.');
    }
}
