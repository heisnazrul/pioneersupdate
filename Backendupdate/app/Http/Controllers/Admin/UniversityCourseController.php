<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IntakeTerm;
use App\Models\Level;
use App\Models\University;
use App\Models\UniversityCourse;
use App\Models\UniversityCourseCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UniversityCourseController extends Controller
{
    public function index(): View
    {
        $courses = UniversityCourse::with(['university', 'level', 'courseCatalog.subjectArea'])
            ->latest()
            ->paginate(15);

        return view('admin.university-courses.index', compact('courses'));
    }

    public function create(): View
    {
        $universities = University::orderBy('name')->get();
        $levels = Level::where('is_active', true)->orderBy('sort_order')->get();
        $catalogs = UniversityCourseCatalog::where('is_active', true)->orderBy('name')->get();
        $intakes = IntakeTerm::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.university-courses.create', compact('universities', 'levels', 'catalogs', 'intakes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['is_active'] = $request->boolean('is_active', true);

        $course = UniversityCourse::create($data);
        $course->intakeTerms()->sync($this->buildIntakeSyncData($request->input('intakes', [])));

        return redirect()->route('admin.university-courses.index')->with('success', 'University course created successfully.');
    }

    public function edit(UniversityCourse $universityCourse): View
    {
        $universities = University::orderBy('name')->get();
        $levels = Level::where('is_active', true)->orderBy('sort_order')->get();
        $catalogs = UniversityCourseCatalog::where('is_active', true)->orderBy('name')->get();
        $intakes = IntakeTerm::where('is_active', true)->orderBy('sort_order')->get();
        $selectedIntakes = $universityCourse->intakeTerms->keyBy('id');

        return view('admin.university-courses.edit', compact('universityCourse', 'universities', 'levels', 'catalogs', 'intakes', 'selectedIntakes'));
    }

    public function update(Request $request, UniversityCourse $universityCourse): RedirectResponse
    {
        $data = $this->validateData($request, $universityCourse);
        $data['is_active'] = $request->boolean('is_active');

        $universityCourse->update($data);
        $universityCourse->intakeTerms()->sync($this->buildIntakeSyncData($request->input('intakes', [])));

        return redirect()->route('admin.university-courses.index')->with('success', 'University course updated successfully.');
    }

    public function destroy(UniversityCourse $universityCourse): RedirectResponse
    {
        $universityCourse->delete();

        return redirect()->route('admin.university-courses.index')->with('success', 'University course deleted successfully.');
    }

    private function validateData(Request $request, ?UniversityCourse $course = null): array
    {
        $uniqueCombo = Rule::unique('university_courses')
            ->where(fn ($query) => $query
                ->where('university_id', $request->input('university_id'))
                ->where('course_catalog_id', $request->input('course_catalog_id'))
                ->where('level_id', $request->input('level_id')));

        if ($course) {
            $uniqueCombo->ignore($course->id);
        }

        return $request->validate([
            'university_id' => ['required', 'exists:universities,id'],
            'course_catalog_id' => ['required', 'exists:university_course_catalogs,id', $uniqueCombo],
            'level_id' => ['required', 'exists:levels,id'],
            'duration_value' => ['nullable', 'integer', 'min:1'],
            'duration_unit' => ['nullable', Rule::in(['month', 'year', 'week'])],
            'first_year_fee' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'overview' => ['nullable', 'string'],
            'ar_overview' => ['nullable', 'string'],
            'awarding_body' => ['nullable', 'string', 'max:255'],
            'ar_awarding_body' => ['nullable', 'string', 'max:255'],
            'degree_requirement' => ['nullable', 'string'],
            'language_requirement' => ['nullable', 'string'],
            'intakes' => ['nullable', 'array'],
            'intakes.*.selected' => ['nullable', 'boolean'],
            'intakes.*.deadline_date' => ['nullable', 'date'],
            'intakes.*.start_date' => ['nullable', 'date'],
            'intakes.*.id' => ['nullable', 'exists:intake_terms,id'],
        ]);
    }

    private function buildIntakeSyncData(array $intakes): array
    {
        $syncData = [];

        foreach ($intakes as $intakeId => $data) {
            if (!empty($data['selected'])) {
                $syncData[$intakeId] = [
                    'deadline_date' => $data['deadline_date'] ?: null,
                    'start_date' => $data['start_date'] ?: null,
                    'is_active' => true,
                ];
            }
        }

        return $syncData;
    }
}
