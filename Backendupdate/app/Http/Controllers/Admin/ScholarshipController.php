<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\University;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ScholarshipController extends Controller
{
    public function index(): View
    {
        $scholarships = Scholarship::with('university')->orderBy('name')->paginate(15);

        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create(): View
    {
        $universities = University::orderBy('name')->get();

        return view('admin.scholarships.create', compact('universities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->generateUniqueSlug($data['name'], $data['university_id'] ?? null);
        $data['is_active'] = $request->boolean('is_active', true);

        Scholarship::create($data);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship created successfully.');
    }

    public function edit(Scholarship $scholarship): View
    {
        $universities = University::orderBy('name')->get();

        return view('admin.scholarships.edit', compact('scholarship', 'universities'));
    }

    public function update(Request $request, Scholarship $scholarship): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active');

        if ($scholarship->name !== $data['name'] || $scholarship->university_id !== ($data['university_id'] ?? null)) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $data['university_id'] ?? null, $scholarship->id);
        }

        $scholarship->update($data);

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship): RedirectResponse
    {
        $scholarship->delete();

        return redirect()->route('admin.scholarships.index')->with('success', 'Scholarship deleted successfully.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'university_id' => ['nullable', 'exists:universities,id'],
            'provider_name' => ['nullable', 'required_without:university_id', 'string', 'max:255'],
            'ar_provider_name' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'summary' => ['nullable', 'string', 'max:255'],
            'ar_summary' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'amount_type' => ['required', 'in:fixed,percentage,variable'],
            'amount_value' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'min:0'],
            'deadline_date' => ['nullable', 'date'],
            'eligible_nationalities' => ['nullable', 'array'],
            'eligible_nationalities.*' => ['nullable', 'string', 'max:100'],
            'eligibility_text' => ['nullable', 'string'],
            'ar_eligibility_text' => ['nullable', 'string'],
            'apply_link' => ['nullable', 'url', 'max:500'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['nullable', 'string', 'max:100'],
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $universityId = null, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'scholarship';
        $slug = $baseSlug;
        $counter = 1;

        while (
            Scholarship::query()
                ->when(is_null($universityId), fn ($query) => $query->whereNull('university_id'), fn ($query) => $query->where('university_id', $universityId))
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
