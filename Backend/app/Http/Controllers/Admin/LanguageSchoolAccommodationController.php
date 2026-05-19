<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolAccommodation;
use App\Models\LanguageSchoolBranch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageSchoolAccommodationController extends Controller
{
    public function index(): View
    {
        $filters = [
            'country_id' => request('country_id'),
            'city_id' => request('city_id'),
            'school_id' => request('school_id'),
            'branch_id' => request('branch_id'),
        ];

        $items = LanguageSchoolAccommodation::with('branch.school', 'tag', 'bedroomType', 'bathroomType', 'mealPlan')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branch.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branch', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->when($filters['school_id'], fn($q) => $q->whereHas('branch', fn($qq) => $qq->where('language_school_id', $filters['school_id'])))
            ->when($filters['branch_id'], fn($q) => $q->where('branch_id', $filters['branch_id']))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $countries = \App\Models\Country::whereHas('cities.languageSchoolBranches.accommodations')->orderBy('name')->get(['id','name']);
        $cities = \App\Models\City::whereHas('languageSchoolBranches.accommodations')
            ->when($filters['country_id'], fn($q) => $q->where('country_id', $filters['country_id']))
            ->orderBy('name')->get(['id','name','country_id']);
        $schools = \App\Models\LanguageSchool::whereHas('branches.accommodations')
            ->when($filters['country_id'], fn($q) => $q->whereHas('branches.city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->when($filters['city_id'], fn($q) => $q->whereHas('branches', fn($qq) => $qq->where('city_id', $filters['city_id'])))
            ->orderBy('name')->get();
        $branches = LanguageSchoolBranch::whereHas('accommodations')
            ->when($filters['school_id'], fn($q) => $q->where('language_school_id', $filters['school_id']))
            ->when($filters['city_id'], fn($q) => $q->where('city_id', $filters['city_id']))
            ->when($filters['country_id'], fn($q) => $q->whereHas('city', fn($qq) => $qq->where('country_id', $filters['country_id'])))
            ->with('school')
            ->orderBy('slug')
            ->get();

        return view('admin.language-school-accommodations.index', [
            'accommodations' => $items,
            'filters' => $filters,
            'countries' => $countries,
            'cities' => $cities,
            'schools' => $schools,
            'branches' => $branches,
        ]);
    }

    public function create(): View
    {
        $branches = LanguageSchoolBranch::with('school')->orderBy('slug')->get();
        $bedroomTypes = \App\Models\BedroomType::orderBy('name')->get();
        $bathroomTypes = \App\Models\BathroomType::orderBy('name')->get();
        $rooms = $bedroomTypes; // reuse bedroom types for room options if needed
        $cities = \App\Models\City::orderBy('name')->get(['id','name']);
        $countries = \App\Models\Country::orderBy('name')->get(['id','name']);
        $tags = \App\Models\LanguageCourseTag::orderBy('name')->get(['id','name']);
        $mealPlans = \App\Models\MealPlan::orderBy('name')->get(['id','name']);
        return view('admin.language-school-accommodations.create', compact('branches','bedroomTypes','bathroomTypes','rooms','cities','countries','tags','mealPlans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        LanguageSchoolAccommodation::create($data);

        return redirect()->route('admin.language-school-accommodations.index')->with('success', 'Accommodation created.');
    }

    public function edit(LanguageSchoolAccommodation $languageSchoolAccommodation): View
    {
        $branches = LanguageSchoolBranch::with('school')->orderBy('slug')->get();
        return view('admin.language-school-accommodations.edit', [
            'item' => $languageSchoolAccommodation,
            'accommodation' => $languageSchoolAccommodation,
            'branches' => $branches,
            'bedroomTypes' => \App\Models\BedroomType::orderBy('name')->get(),
            'bathroomTypes' => \App\Models\BathroomType::orderBy('name')->get(),
            'tags' => \App\Models\LanguageCourseTag::orderBy('name')->get(['id','name']),
            'mealPlans' => \App\Models\MealPlan::orderBy('name')->get(['id','name']),
        ]);
    }

    public function update(Request $request, LanguageSchoolAccommodation $languageSchoolAccommodation): RedirectResponse
    {
        $data = $this->validateData($request);

        $languageSchoolAccommodation->update($data);

        return redirect()->route('admin.language-school-accommodations.index')->with('success', 'Accommodation updated.');
    }

    public function destroy(LanguageSchoolAccommodation $languageSchoolAccommodation): RedirectResponse
    {
        $languageSchoolAccommodation->delete();

        return redirect()->route('admin.language-school-accommodations.index')->with('success', 'Accommodation deleted.');
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'branch_id' => ['required', 'exists:language_school_branches,id'],
            'language_course_tag_id' => ['nullable', 'exists:language_course_tags,id'],
            'bedroom_type_id' => ['required', 'exists:bedroom_types,id'],
            'bathroom_type_id' => ['required', 'exists:bathroom_types,id'],
            'meal_plan_id' => ['required', 'exists:meal_plans,id'],
            'title' => ['required', 'string', 'max:255'],
            'ar_title' => ['nullable', 'string', 'max:255'],
            'required_age' => ['nullable', 'integer', 'min:0'],
            'fee_per_week' => ['required', 'numeric', 'min:0'],
            'admin_charge' => ['nullable', 'numeric', 'min:0'],
            'under18_supplement_per_week' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
