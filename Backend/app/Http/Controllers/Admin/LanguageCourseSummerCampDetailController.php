<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageCourseSummerCampDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageCourseSummerCampDetailController extends Controller
{
    public function index(): View
    {
        $details = LanguageCourseSummerCampDetail::query()
            ->with(['camp.branch.school'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-course-summer-camp-details.index', compact('details'));
    }

    public function create(): View
    {
        return view('admin.language-course-summer-camp-details.create', [
            'camps' => $this->availableCamps(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['images'] = $this->storeUploadedImages($request);

        LanguageCourseSummerCampDetail::create($data);

        return redirect()->route('admin.language-course-summer-camp-details.index')
            ->with('success', 'Summer camp detail created successfully.');
    }

    public function edit(LanguageCourseSummerCampDetail $detail): View
    {
        return view('admin.language-course-summer-camp-details.edit', [
            'detail' => $detail,
            'camps' => $this->campsForEdit(),
        ]);
    }

    public function update(Request $request, LanguageCourseSummerCampDetail $detail): RedirectResponse
    {
        $data = $this->validateData($request, $detail);

        $currentImages = collect($detail->images ?? [])->filter()->values()->all();
        $keptImages = collect($request->input('existing_images', []))
            ->filter(fn ($path) => in_array($path, $currentImages, true))
            ->values()
            ->all();
        $newImages = $this->storeUploadedImages($request);
        $images = array_values(array_unique(array_merge($keptImages, $newImages)));
        $removedImages = array_values(array_diff($currentImages, $images));

        foreach ($removedImages as $path) {
            Storage::disk('public')->delete($path);
        }

        $data['images'] = $images;
        $detail->update($data);

        return redirect()->route('admin.language-course-summer-camp-details.index')
            ->with('success', 'Summer camp detail updated successfully.');
    }

    public function destroy(LanguageCourseSummerCampDetail $detail): RedirectResponse
    {
        foreach ((array) $detail->images as $path) {
            Storage::disk('public')->delete($path);
        }

        $detail->delete();

        return redirect()->route('admin.language-course-summer-camp-details.index')
            ->with('success', 'Summer camp detail deleted.');
    }

    private function validateData(Request $request, ?LanguageCourseSummerCampDetail $detail = null): array
    {
        return $request->validate([
            'camp_id' => [
                'required',
                'integer',
                'exists:language_course_summer_camps,id',
                Rule::unique('language_course_summer_camp_details', 'camp_id')->ignore($detail?->id),
            ],
            'overview' => ['nullable', 'string'],
            'ar_overview' => ['nullable', 'string'],
            'academics' => ['nullable', 'string'],
            'ar_academics' => ['nullable', 'string'],
            'activities' => ['nullable', 'string'],
            'ar_activities' => ['nullable', 'string'],
            'accommodation' => ['nullable', 'string'],
            'ar_accommodation' => ['nullable', 'string'],
            'safeguarding' => ['nullable', 'string'],
            'ar_safeguarding' => ['nullable', 'string'],
            'existing_images' => ['nullable', 'array'],
            'existing_images.*' => ['string', 'max:255'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
        ], [], [
            'camp_id' => 'summer camp',
        ]);
    }

    private function storeUploadedImages(Request $request): array
    {
        if (!$request->hasFile('images')) {
            return [];
        }

        $paths = [];
        foreach ((array) $request->file('images') as $file) {
            if ($file) {
                $paths[] = $file->store('summer-camp-details', 'public');
            }
        }

        return $paths;
    }

    private function availableCamps()
    {
        return LanguageCourseSummerCamp::query()
            ->with(['branch.school'])
            ->whereDoesntHave('detail')
            ->orderBy('name')
            ->get();
    }

    private function campsForEdit()
    {
        return LanguageCourseSummerCamp::query()
            ->with(['branch.school'])
            ->orderBy('name')
            ->get();
    }
}
