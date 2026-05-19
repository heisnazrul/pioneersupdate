<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MealPlan;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    public function index()
    {
        $plans = MealPlan::latest()->paginate(20);
        return view('admin.meal-plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.meal-plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
        ]);
        MealPlan::create($data);
        return redirect()->route('admin.meal-plans.index')->with('success', 'Meal plan created.');
    }

    public function edit(MealPlan $mealPlan)
    {
        return view('admin.meal-plans.edit', compact('mealPlan'));
    }

    public function update(Request $request, MealPlan $mealPlan)
    {
        $data = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
        ]);
        $mealPlan->update($data);
        return redirect()->route('admin.meal-plans.index')->with('success', 'Meal plan updated.');
    }

    public function destroy(MealPlan $mealPlan)
    {
        $mealPlan->delete();
        return redirect()->route('admin.meal-plans.index')->with('success', 'Meal plan deleted.');
    }
}
