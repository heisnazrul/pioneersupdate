<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::with('university')->orderBy('name')->paginate(15);
        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        return view('admin.scholarships.create', compact('universities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'nullable|exists:universities,id',
            'provider_name' => 'nullable|required_without:university_id|string|max:255',
            'ar_provider_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'ar_name' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:255',
            'ar_summary' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'amount_type' => 'required|in:fixed,percentage,variable',
            'amount_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'deadline_date' => 'nullable|date',
            'eligible_nationalities' => 'nullable|array',
            'eligibility_text' => 'nullable|string',
            'ar_eligibility_text' => 'nullable|string',
            'apply_link' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        $slug = Str::slug($validated['name']);

        if (!empty($validated['university_id'])) {
            $count = Scholarship::where('university_id', $validated['university_id'])
                ->where('slug', $slug)
                ->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
        } else {
            $count = Scholarship::whereNull('university_id')->where('slug', $slug)->count();
            if ($count > 0) {
                $slug .= '-' . ($count + 1);
            }
        }

        $validated['slug'] = $slug;

        Scholarship::create($validated);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship created successfully.');
    }

    public function edit(Scholarship $scholarship)
    {
        $universities = University::orderBy('name')->get();
        return view('admin.scholarships.edit', compact('scholarship', 'universities'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'university_id' => 'nullable|exists:universities,id',
            'provider_name' => 'nullable|required_without:university_id|string|max:255',
            'ar_provider_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'ar_name' => 'nullable|string|max:255',
            'summary' => 'nullable|string|max:255',
            'ar_summary' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'amount_type' => 'required|in:fixed,percentage,variable',
            'amount_value' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|size:3',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0',
            'deadline_date' => 'nullable|date',
            'eligible_nationalities' => 'nullable|array',
            'eligibility_text' => 'nullable|string',
            'ar_eligibility_text' => 'nullable|string',
            'apply_link' => 'nullable|url|max:500',
            'is_active' => 'boolean',
            'tags' => 'nullable|array',
        ]);

        if ($request->name !== $scholarship->name) {
            $slug = Str::slug($validated['name']);
            if (!empty($validated['university_id'])) {
                $count = Scholarship::where('university_id', $validated['university_id'])
                    ->where('slug', $slug)
                    ->where('id', '!=', $scholarship->id)
                    ->count();
                if ($count > 0) {
                    $slug .= '-' . ($count + 1);
                }
            } else {
                $count = Scholarship::whereNull('university_id')
                    ->where('slug', $slug)
                    ->where('id', '!=', $scholarship->id)
                    ->count();
                if ($count > 0) {
                    $slug .= '-' . ($count + 1);
                }
            }
            $validated['slug'] = $slug;
        }

        $scholarship->update($validated);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();
        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship deleted successfully.');
    }
}
