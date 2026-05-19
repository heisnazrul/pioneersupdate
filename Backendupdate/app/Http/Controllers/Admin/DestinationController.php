<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Destination;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DestinationController extends Controller
{
    public function index(): View
    {
        $destinations = Destination::with('country')->latest()->paginate(15);

        return view('admin.destinations.index', compact('destinations'));
    }

    public function create(): View
    {
        $countries = Country::active()->orderBy('name')->get();

        return view('admin.destinations.create', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['image_url'] = $request->input('gallery_image_url');
        unset($data['gallery_image_url']);

        $destination = Destination::create($data);
        $this->syncChildren($destination, $request);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination created successfully.');
    }

    public function edit(Destination $destination): View
    {
        $countries = Country::active()->orderBy('name')->get();
        $destination->load(['features', 'stats', 'intakes', 'faqs', 'requirements']);

        return view('admin.destinations.edit', compact('destination', 'countries'));
    }

    public function update(Request $request, Destination $destination): RedirectResponse
    {
        $data = $this->validateData($request, $destination);
        $data['is_active'] = $request->boolean('is_active');
        $data['image_url'] = $request->input('gallery_image_url');
        unset($data['gallery_image_url']);

        $destination->update($data);
        $this->syncChildren($destination, $request);

        return redirect()->route('admin.destinations.index')->with('success', 'Destination updated successfully.');
    }

    public function destroy(Destination $destination): RedirectResponse
    {
        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('success', 'Destination deleted successfully.');
    }

    private function validateData(Request $request, ?Destination $destination = null): array
    {
        return $request->validate([
            'country_id' => ['nullable', 'exists:countries,id'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('destinations', 'slug')->ignore($destination?->id)],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['nullable', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'ar_region' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'gallery_image_url' => ['nullable', 'string', 'max:255'],
            'short_pitch' => ['nullable', 'string'],
            'ar_short_pitch' => ['nullable', 'string'],
            'tuition_range' => ['nullable', 'string', 'max:255'],
            'ar_tuition_range' => ['nullable', 'string', 'max:255'],
            'visa_timeline' => ['nullable', 'string', 'max:255'],
            'ar_visa_timeline' => ['nullable', 'string', 'max:255'],
            'work_rights' => ['nullable', 'string', 'max:255'],
            'ar_work_rights' => ['nullable', 'string', 'max:255'],
            'scholarships_summary' => ['nullable', 'string', 'max:255'],
            'ar_scholarships_summary' => ['nullable', 'string', 'max:255'],
            'entry_req_gpa' => ['nullable', 'string'],
            'ar_entry_req_gpa' => ['nullable', 'string'],
            'entry_req_language' => ['nullable', 'string'],
            'ar_entry_req_language' => ['nullable', 'string'],
            'university_count' => ['nullable', 'integer', 'min:0'],
            'features' => ['nullable', 'array'],
            'features.*.feature' => ['nullable', 'string', 'max:255'],
            'features.*.ar_feature' => ['nullable', 'string', 'max:255'],
            'stats' => ['nullable', 'array'],
            'stats.*.label' => ['nullable', 'string', 'max:255'],
            'stats.*.ar_label' => ['nullable', 'string', 'max:255'],
            'stats.*.value' => ['nullable', 'string', 'max:255'],
            'stats.*.ar_value' => ['nullable', 'string', 'max:255'],
            'intakes' => ['nullable', 'array'],
            'intakes.*.month' => ['nullable', 'string', 'max:255'],
            'intakes.*.ar_month' => ['nullable', 'string', 'max:255'],
            'intakes.*.event' => ['nullable', 'string', 'max:255'],
            'intakes.*.ar_event' => ['nullable', 'string', 'max:255'],
            'faqs' => ['nullable', 'array'],
            'faqs.*.question' => ['nullable', 'string'],
            'faqs.*.ar_question' => ['nullable', 'string'],
            'faqs.*.answer' => ['nullable', 'string'],
            'faqs.*.ar_answer' => ['nullable', 'string'],
            'requirements' => ['nullable', 'array'],
            'requirements.*.requirement' => ['nullable', 'string', 'max:255'],
            'requirements.*.ar_requirement' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function syncChildren(Destination $destination, Request $request): void
    {
        $destination->features()->delete();
        $destination->stats()->delete();
        $destination->intakes()->delete();
        $destination->faqs()->delete();
        $destination->requirements()->delete();

        foreach ($this->filteredRows($request->input('features', []), 'feature') as $row) {
            $destination->features()->create($row);
        }

        foreach ($this->filteredRows($request->input('stats', []), 'label', 'value') as $row) {
            $destination->stats()->create($row);
        }

        foreach ($this->filteredRows($request->input('intakes', []), 'month', 'event') as $row) {
            $destination->intakes()->create($row);
        }

        foreach ($this->filteredRows($request->input('faqs', []), 'question', 'answer') as $row) {
            $destination->faqs()->create($row);
        }

        foreach ($this->filteredRows($request->input('requirements', []), 'requirement') as $row) {
            $destination->requirements()->create($row);
        }
    }

    private function filteredRows(array $rows, string ...$requiredKeys): array
    {
        return collect($rows)
            ->filter(function ($row) use ($requiredKeys) {
                foreach ($requiredKeys as $key) {
                    if (!empty($row[$key])) {
                        return true;
                    }
                }

                return false;
            })
            ->map(fn ($row) => array_map(fn ($value) => $value === '' ? null : $value, $row))
            ->values()
            ->all();
    }
}
