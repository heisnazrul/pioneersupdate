<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\LanguageCourseCategory;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageSchoolBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LanguageCourseSummerCampController extends Controller
{
    public function index(): View
    {
        $camps = LanguageCourseSummerCamp::with(['branch.school', 'branch.city', 'courseType'])
            ->latest()
            ->paginate(20);

        return view('admin.language-course-summer-camps.index', compact('camps'));
    }

    public function create(): View
    {
        $branches = LanguageSchoolBranch::with(['school', 'city'])->where('is_active', 'yes')->get();
        $categories = LanguageCourseCategory::active()->orderBy('name_en')->get();
        $galleryItems = Gallery::where('use_case', 'camps')->get();

        return view('admin.language-course-summer-camps.create', compact('branches', 'categories', 'galleryItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateRequest($request);

        if (!$request->filled('slug')) {
            $data['slug'] = $this->generateUniqueSlug($data['name']);
        }

        $detailData = $this->extractDetailData($request);

        DB::transaction(function () use ($data, $detailData, $request) {
            $data['thumbnail'] = $request->input('gallery_thumbnail');
            unset($data['gallery_thumbnail']);
            $data['visible'] = $request->boolean('visible', true);

            $camp = LanguageCourseSummerCamp::create($data);
            $camp->detail()->create($detailData);
        });

        return redirect()->route('admin.language-course-summer-camps.index')
            ->with('success', 'Summer camp created successfully.');
    }

    public function edit(LanguageCourseSummerCamp $languageCourseSummerCamp): View
    {
        $languageCourseSummerCamp->load('detail');
        $branches = LanguageSchoolBranch::with(['school', 'city'])->get();
        $categories = LanguageCourseCategory::active()->orderBy('name_en')->get();
        $galleryItems = Gallery::where('use_case', 'camps')->get();

        return view('admin.language-course-summer-camps.edit', compact('languageCourseSummerCamp', 'branches', 'categories', 'galleryItems'));
    }

    public function update(Request $request, LanguageCourseSummerCamp $languageCourseSummerCamp): RedirectResponse
    {
        $data = $this->validateRequest($request, $languageCourseSummerCamp);

        if (!$request->filled('slug')) {
            $data['slug'] = $this->generateUniqueSlug($data['name'], $languageCourseSummerCamp->id);
        }

        $detailData = $this->extractDetailData($request);

        DB::transaction(function () use ($data, $detailData, $request, $languageCourseSummerCamp) {
            $data['thumbnail'] = $request->input('gallery_thumbnail');
            unset($data['gallery_thumbnail']);
            $data['visible'] = $request->boolean('visible');

            $languageCourseSummerCamp->update($data);
            $languageCourseSummerCamp->detail()->updateOrCreate(
                ['camp_id' => $languageCourseSummerCamp->id],
                $detailData,
            );
        });

        return redirect()->route('admin.language-course-summer-camps.index')
            ->with('success', 'Summer camp updated successfully.');
    }

    public function destroy(LanguageCourseSummerCamp $languageCourseSummerCamp): RedirectResponse
    {
        $languageCourseSummerCamp->delete();

        return redirect()->route('admin.language-course-summer-camps.index')
            ->with('success', 'Summer camp deleted.');
    }

    private function validateRequest(Request $request, ?LanguageCourseSummerCamp $camp = null): array
    {
        return $request->validate([
            'slug' => [
                'nullable',
                'string',
                'max:160',
                Rule::unique('language_course_summer_camps', 'slug')->ignore($camp?->id),
            ],
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'course_type_id' => ['required', 'exists:language_course_categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'ar_name' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
            'required_level' => ['nullable', 'string', 'max:10'],
            'study_time' => ['nullable', 'string', 'max:10'],
            'lessons_per_week' => ['nullable', 'integer', 'min:0'],
            'age_range' => ['nullable', 'string', 'max:50'],
            'start_date' => ['nullable', 'string', 'max:10'],
            'payment_deadline' => ['nullable', 'date'],
            'fee_type' => ['required', Rule::in(['flat', 'weekly'])],
            'fee_amount' => ['required', 'numeric', 'min:0'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'gallery_thumbnail' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['draft', 'published', 'suspended'])],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'string', 'max:255'],
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
        ]);
    }

    private function extractDetailData(Request $request): array
    {
        return [
            'overview' => $request->input('overview'),
            'ar_overview' => $request->input('ar_overview'),
            'academics' => $request->input('academics'),
            'ar_academics' => $request->input('ar_academics'),
            'activities' => $request->input('activities'),
            'ar_activities' => $request->input('ar_activities'),
            'accommodation' => $request->input('accommodation'),
            'ar_accommodation' => $request->input('ar_accommodation'),
            'safeguarding' => $request->input('safeguarding'),
            'ar_safeguarding' => $request->input('ar_safeguarding'),
            'images' => $request->input('gallery_images', []),
        ];
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'summer-camp';
        $slug = $baseSlug;
        $counter = 1;

        while (LanguageCourseSummerCamp::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
