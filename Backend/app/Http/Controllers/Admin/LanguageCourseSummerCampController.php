<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageCourseSummerCamp;
use App\Models\LanguageCourseType;
use App\Models\LanguageCourseTag;
use App\Models\LanguageSchoolBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LanguageCourseSummerCampController extends Controller
{
    public function index(): View
    {
        $camps = LanguageCourseSummerCamp::query()
            ->with(['branch.school', 'courseType', 'tag'])
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.language-course-summer-camps.index', compact('camps'));
    }

    public function create(): View
    {
        return view('admin.language-course-summer-camps.create', [
            'branches' => $this->branches(),
            'types' => $this->types(),
            'tags' => $this->tags(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = Str::slug($data['name'].'-'.uniqid());

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('language-summer-camps', 'public');
        }

        $camp = LanguageCourseSummerCamp::create($data);
        $camp->detail()->create([]); // create empty detail row to avoid missing relation

        return redirect()->route('admin.language-course-summer-camps.index')
            ->with('success', 'Summer camp created successfully.');
    }

    public function edit(LanguageCourseSummerCamp $languageCourseSummerCamp): View
    {
        return view('admin.language-course-summer-camps.edit', [
            'camp' => $languageCourseSummerCamp,
            'branches' => $this->branches(),
            'types' => $this->types(),
            'tags' => $this->tags(),
            'detail' => $languageCourseSummerCamp->detail,
        ]);
    }

    public function update(Request $request, LanguageCourseSummerCamp $languageCourseSummerCamp): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('thumbnail')) {
            if ($languageCourseSummerCamp->thumbnail) {
                Storage::disk('public')->delete($languageCourseSummerCamp->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('language-summer-camps', 'public');
        } else {
            unset($data['thumbnail']);
        }

        $languageCourseSummerCamp->update($data);

        $detailData = $request->validate([
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
        $languageCourseSummerCamp->detail()->updateOrCreate(['camp_id' => $languageCourseSummerCamp->id], $detailData);

        return redirect()->route('admin.language-course-summer-camps.index')
            ->with('success', 'Summer camp updated successfully.');
    }

    public function destroy(LanguageCourseSummerCamp $languageCourseSummerCamp): RedirectResponse
    {
        $languageCourseSummerCamp->delete();
        return redirect()->route('admin.language-course-summer-camps.index')
            ->with('success', 'Summer camp deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'course_type_id' => ['required', 'exists:language_course_types,id'],
            'tag_id' => ['nullable', 'exists:language_course_tags,id'],
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
            'fee_type' => ['required', 'in:flat,weekly'],
            'fee_amount' => ['required', 'numeric', 'min:0'],
            'registration_fee' => ['nullable', 'numeric', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'max:4096'],
            'visible' => ['nullable', 'boolean'],
            'status' => ['required', 'in:draft,published,suspended'],
        ]);
    }

    private function branches()
    {
        return LanguageSchoolBranch::with('school')->orderBy('id')->get(['id', 'language_school_id', 'slug']);
    }

    private function types()
    {
        return LanguageCourseType::orderBy('name')->get(['id', 'name']);
    }

    private function tags()
    {
        return LanguageCourseTag::orderBy('name')->get(['id', 'name']);
    }
}
