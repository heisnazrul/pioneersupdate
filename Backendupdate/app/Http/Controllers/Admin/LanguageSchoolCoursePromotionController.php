<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageSchoolCoursePromotion;
use App\Models\LanguageSchoolCourse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LanguageSchoolCoursePromotionController extends Controller
{
    public function index(): View
    {
        $promotions = LanguageSchoolCoursePromotion::with(['course.branch.school', 'course.category'])
            ->latest()
            ->paginate(20);
        return view('admin.language-school-course-promotions.index', compact('promotions'));
    }

    public function create(): View
    {
        $courses = LanguageSchoolCourse::with(['branch.school', 'category'])->get();
        return view('admin.language-school-course-promotions.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id'            => 'required|exists:language_school_courses,id',
            'promotion_percentage' => 'required|numeric|min:0|max:100',
            'promo_from'           => 'nullable|date',
            'promo_to'             => 'nullable|date|after_or_equal:promo_from',
            'is_active'            => 'required|in:yes,no',
        ]);

        LanguageSchoolCoursePromotion::create($data);

        return redirect()->route('admin.language-school-course-promotions.index')
            ->with('success', 'Promotion created successfully.');
    }

    public function edit(LanguageSchoolCoursePromotion $languageSchoolCoursePromotion): View
    {
        $courses = LanguageSchoolCourse::with(['branch.school', 'category'])->get();
        return view('admin.language-school-course-promotions.edit', compact('languageSchoolCoursePromotion', 'courses'));
    }

    public function update(Request $request, LanguageSchoolCoursePromotion $languageSchoolCoursePromotion): RedirectResponse
    {
        $data = $request->validate([
            'course_id'            => 'required|exists:language_school_courses,id',
            'promotion_percentage' => 'required|numeric|min:0|max:100',
            'promo_from'           => 'nullable|date',
            'promo_to'             => 'nullable|date|after_or_equal:promo_from',
            'is_active'            => 'required|in:yes,no',
        ]);

        $languageSchoolCoursePromotion->update($data);

        return redirect()->route('admin.language-school-course-promotions.index')
            ->with('success', 'Promotion updated successfully.');
    }

    public function destroy(LanguageSchoolCoursePromotion $languageSchoolCoursePromotion): RedirectResponse
    {
        $languageSchoolCoursePromotion->delete();
        return redirect()->route('admin.language-school-course-promotions.index')
            ->with('success', 'Promotion deleted.');
    }
}
