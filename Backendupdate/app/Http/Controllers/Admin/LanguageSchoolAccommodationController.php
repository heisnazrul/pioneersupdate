<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolAccommodation;
use App\Models\LanguageSchoolBranch;
use App\Models\AccommodationType;
use App\Models\BedroomType;
use App\Models\BathroomType;
use App\Models\MealPlan;
use Illuminate\Http\Request;

class LanguageSchoolAccommodationController extends Controller
{
    public function index()
    {
        $accommodations = LanguageSchoolAccommodation::with(['branch.school', 'type'])->latest()->paginate(20);
        return view('admin.language-school-accommodations.index', compact('accommodations'));
    }

    public function create()
    {
        $branches = LanguageSchoolBranch::with('school')->get();
        $types = AccommodationType::all();
        $bedroomTypes = BedroomType::all();
        $bathroomTypes = BathroomType::all();
        $mealPlans = MealPlan::all();
        return view('admin.language-school-accommodations.create', compact('branches', 'types', 'bedroomTypes', 'bathroomTypes', 'mealPlans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'branch_id'               => 'required|exists:language_school_branches,id',
            'accommodation_type_id'   => 'required|exists:accommodation_types,id',
            'name'                    => 'required|string|max:255',
            'name_ar'                 => 'nullable|string|max:255',
            'bedroom_type_id'         => 'nullable|exists:bedroom_types,id',
            'bathroom_type_id'        => 'nullable|exists:bathroom_types,id',
            'meal_plan_id'            => 'nullable|exists:meal_plans,id',
            'weekly_fee'              => 'required|numeric|min:0',
            'admin_fee'               => 'required|numeric|min:0',
            'security_deposit'        => 'required|numeric|min:0',
            'min_age'                 => 'nullable|integer|min:0',
            'under_18_supplement_fee' => 'required|numeric|min:0',
            'summer_supplement_fee'   => 'required|numeric|min:0',
            'summer_start_date'       => 'nullable|date',
            'summer_end_date'         => 'nullable|date|after_or_equal:summer_start_date',
            'winter_supplement_fee'   => 'required|numeric|min:0',
            'winter_start_date'       => 'nullable|date',
            'winter_end_date'         => 'nullable|date|after_or_equal:winter_start_date',
            'other_supplement_name'   => 'nullable|string|max:255',
            'other_supplement_fee'    => 'required|numeric|min:0',
            'other_start_date'        => 'nullable|date',
            'other_end_date'          => 'nullable|date|after_or_equal:other_start_date',
            'is_active'               => 'required|in:yes,no',
        ]);

        LanguageSchoolAccommodation::create($data);
        return redirect()->route('admin.language-school-accommodations.index')->with('success', 'Accommodation created.');
    }

    public function edit(LanguageSchoolAccommodation $languageSchoolAccommodation)
    {
        $branches = LanguageSchoolBranch::with('school')->get();
        $types = AccommodationType::all();
        $bedroomTypes = BedroomType::all();
        $bathroomTypes = BathroomType::all();
        $mealPlans = MealPlan::all();
        return view('admin.language-school-accommodations.edit', compact('languageSchoolAccommodation', 'branches', 'types', 'bedroomTypes', 'bathroomTypes', 'mealPlans'));
    }

    public function update(Request $request, LanguageSchoolAccommodation $languageSchoolAccommodation)
    {
        $data = $request->validate([
            'branch_id'               => 'required|exists:language_school_branches,id',
            'accommodation_type_id'   => 'required|exists:accommodation_types,id',
            'name'                    => 'required|string|max:255',
            'name_ar'                 => 'nullable|string|max:255',
            'bedroom_type_id'         => 'nullable|exists:bedroom_types,id',
            'bathroom_type_id'        => 'nullable|exists:bathroom_types,id',
            'meal_plan_id'            => 'nullable|exists:meal_plans,id',
            'weekly_fee'              => 'required|numeric|min:0',
            'admin_fee'               => 'required|numeric|min:0',
            'security_deposit'        => 'required|numeric|min:0',
            'min_age'                 => 'nullable|integer|min:0',
            'under_18_supplement_fee' => 'required|numeric|min:0',
            'summer_supplement_fee'   => 'required|numeric|min:0',
            'summer_start_date'       => 'nullable|date',
            'summer_end_date'         => 'nullable|date|after_or_equal:summer_start_date',
            'winter_supplement_fee'   => 'required|numeric|min:0',
            'winter_start_date'       => 'nullable|date',
            'winter_end_date'         => 'nullable|date|after_or_equal:winter_start_date',
            'other_supplement_name'   => 'nullable|string|max:255',
            'other_supplement_fee'    => 'required|numeric|min:0',
            'other_start_date'        => 'nullable|date',
            'other_end_date'          => 'nullable|date|after_or_equal:other_start_date',
            'is_active'               => 'required|in:yes,no',
        ]);

        $languageSchoolAccommodation->update($data);
        return redirect()->route('admin.language-school-accommodations.index')->with('success', 'Accommodation updated.');
    }

    public function destroy(LanguageSchoolAccommodation $languageSchoolAccommodation)
    {
        $languageSchoolAccommodation->delete();
        return redirect()->route('admin.language-school-accommodations.index')->with('success', 'Accommodation deleted.');
    }
}
