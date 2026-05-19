<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MealPlan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MealPlanController extends Controller
{
    public function index(): View
    {
        $mealPlans = MealPlan::query()
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.meal-plans.index', [
            'mealPlans' => $mealPlans,
        ]);
    }

    public function create(): View
    {
        return view('admin.meal-plans.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        MealPlan::create($data);

        return redirect()
            ->route('admin.meal-plans.index')
            ->with('success', 'Meal plan created successfully.');
    }

    public function edit(MealPlan $mealPlan): View
    {
        return view('admin.meal-plans.edit', [
            'mealPlan' => $mealPlan,
        ]);
    }

    public function update(Request $request, MealPlan $mealPlan): RedirectResponse
    {
        $data = $this->validateData($request, $mealPlan);

        $mealPlan->update($data);

        return redirect()
            ->route('admin.meal-plans.index')
            ->with('success', 'Meal plan updated successfully.');
    }

    public function destroy(MealPlan $mealPlan): RedirectResponse
    {
        $mealPlan->delete();

        return redirect()
            ->route('admin.meal-plans.index')
            ->with('success', 'Meal plan deleted successfully.');
    }

    private function validateData(Request $request, ?MealPlan $mealPlan = null): array
    {
        return $request->validate([
            'meal_code' => ['required', 'string', 'max:255', Rule::unique('meal_plans', 'meal_code')->ignore($mealPlan?->id)],
            'name' => ['required', 'string', 'max:255'],
            'ar_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'ar_description' => ['nullable', 'string'],
        ]);
    }
}
