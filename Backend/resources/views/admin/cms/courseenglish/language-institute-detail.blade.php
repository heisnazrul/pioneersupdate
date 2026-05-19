@extends('admin.layouts.layout')

@php
    $topNav = $topNav ?? [];
    $topNavAr = $topNavAr ?? [];
    $courseStep = $courseStep ?? [];
    $courseStepAr = $courseStepAr ?? [];
    $accommodationStep = $accommodationStep ?? [];
    $accommodationStepAr = $accommodationStepAr ?? [];
    $additionalOptions = $additionalOptions ?? [];
    $additionalOptionsAr = $additionalOptionsAr ?? [];
    $sidebarInquiry = $sidebarInquiry ?? [];
    $sidebarInquiryAr = $sidebarInquiryAr ?? [];
    $bookingSummary = $bookingSummary ?? [];
    $bookingSummaryAr = $bookingSummaryAr ?? [];

@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Language Institute Detail</h2>
            <p class="text-sm text-gray-500 mt-1">Edit static text; institute data, course/accommodation cards stay dynamic.</p>
        </div>
    </div>

    {{-- TOP NAV --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Top Navigation</h3>
            <span class="text-xs text-gray-500">Back, compare, save, share labels + icons</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institute-detail.top-nav') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Back label (EN)</label>
                    <input name="back_label" value="{{ old('back_label', $topNav['back_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="back_label_ar" value="{{ old('back_label_ar', $topNavAr['back_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Compare label (EN)</label>
                    <input name="compare_label" value="{{ old('compare_label', $topNav['compare_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="compare_label_ar" value="{{ old('compare_label_ar', $topNavAr['compare_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Save label (EN)</label>
                    <input name="save_label" value="{{ old('save_label', $topNav['save_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="save_label_ar" value="{{ old('save_label_ar', $topNavAr['save_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Share label (EN)</label>
                    <input name="share_label" value="{{ old('share_label', $topNav['share_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="share_label_ar" value="{{ old('share_label_ar', $topNavAr['share_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Back icon URL</label>
                    <input name="back_icon" value="{{ old('back_icon', $topNav['back_icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="back_icon_file" class="mt-2 w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Compare icon URL</label>
                    <input name="compare_icon" value="{{ old('compare_icon', $topNav['compare_icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="compare_icon_file" class="mt-2 w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Save icon URL</label>
                    <input name="save_icon" value="{{ old('save_icon', $topNav['save_icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="save_icon_file" class="mt-2 w-full text-sm">
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Top Nav</button>
            </div>
        </form>
    </div>

    {{-- COURSE STEP --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Course Step</h3>
            <span class="text-xs text-gray-500">Section title, labels, dropdown options</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institute-detail.course-step') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                    <input name="title" value="{{ old('title', $courseStep['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                    <input name="title_ar" value="{{ old('title_ar', $courseStepAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lessons label (EN)</label>
                    <input name="lessons_label" value="{{ old('lessons_label', $courseStep['lessons_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lessons label (AR)</label>
                    <input name="lessons_label_ar" value="{{ old('lessons_label_ar', $courseStepAr['lessons_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hours label (EN)</label>
                    <input name="hours_label" value="{{ old('hours_label', $courseStep['hours_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hours label (AR)</label>
                    <input name="hours_label_ar" value="{{ old('hours_label_ar', $courseStepAr['hours_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Age label (EN)</label>
                    <input name="age_label" value="{{ old('age_label', $courseStep['age_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Age label (AR)</label>
                    <input name="age_label_ar" value="{{ old('age_label_ar', $courseStepAr['age_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Level label (EN)</label>
                    <input name="level_label" value="{{ old('level_label', $courseStep['level_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Level label (AR)</label>
                    <input name="level_label_ar" value="{{ old('level_label_ar', $courseStepAr['level_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price suffix (EN)</label>
                    <input name="price_suffix" value="{{ old('price_suffix', $courseStep['price_suffix'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price suffix (AR)</label>
                    <input name="price_suffix_ar" value="{{ old('price_suffix_ar', $courseStepAr['price_suffix'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Course Step</button>
            </div>
        </form>
    </div>

    {{-- ACCOMMODATION STEP --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Accommodation Step</h3>
            <span class="text-xs text-gray-500">Labels and optional text</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institute-detail.accommodation-step') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                    <input name="title" value="{{ old('title', $accommodationStep['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                    <input name="title_ar" value="{{ old('title_ar', $accommodationStepAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Optional label (EN)</label>
                    <input name="optional_label" value="{{ old('optional_label', $accommodationStep['optional_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Optional label (AR)</label>
                    <input name="optional_label_ar" value="{{ old('optional_label_ar', $accommodationStepAr['optional_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">No accommodation title (EN)</label>
                    <input name="no_accommodation_title" value="{{ old('no_accommodation_title', $accommodationStep['no_accommodation_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No accommodation title (AR)</label>
                    <input name="no_accommodation_title_ar" value="{{ old('no_accommodation_title_ar', $accommodationStepAr['no_accommodation_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No accommodation description (EN)</label>
                    <textarea name="no_accommodation_description" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('no_accommodation_description', $accommodationStep['no_accommodation_description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No accommodation description (AR)</label>
                    <textarea name="no_accommodation_description_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('no_accommodation_description_ar', $accommodationStepAr['no_accommodation_description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price suffix (EN)</label>
                    <input name="price_suffix" value="{{ old('price_suffix', $accommodationStep['price_suffix'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price suffix (AR)</label>
                    <input name="price_suffix_ar" value="{{ old('price_suffix_ar', $accommodationStepAr['price_suffix'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Accommodation Step</button>
            </div>
        </form>
    </div>

    {{-- ADDITIONAL OPTIONS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Additional Options</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institute-detail.additional-options') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $additionalOptions['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $additionalOptionsAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Additional Options</button>
            </div>
        </form>
    </div>

    {{-- SIDEBAR INQUIRY --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Sidebar Inquiry</h3>
            <span class="text-xs text-gray-500">WhatsApp CTA box</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institute-detail.sidebar') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                    <input name="title" value="{{ old('title', $sidebarInquiry['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                    <input name="title_ar" value="{{ old('title_ar', $sidebarInquiryAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                    <textarea name="description" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $sidebarInquiry['description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                    <textarea name="description_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('description_ar', $sidebarInquiryAr['description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Button text (EN)</label>
                    <input name="button_text" value="{{ old('button_text', $sidebarInquiry['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Button text (AR)</label>
                    <input name="button_text_ar" value="{{ old('button_text_ar', $sidebarInquiryAr['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Sidebar Inquiry</button>
            </div>
        </form>
    </div>

    {{-- BOOKING SUMMARY --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Booking Summary</h3>
            <span class="text-xs text-gray-500">Labels and CTA</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institute-detail.booking-summary') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total label (EN)</label>
                    <input name="total_label" value="{{ old('total_label', $bookingSummary['total_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total label (AR)</label>
                    <input name="total_label_ar" value="{{ old('total_label_ar', $bookingSummaryAr['total_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Study dates label (EN)</label>
                    <input name="study_dates_label" value="{{ old('study_dates_label', $bookingSummary['study_dates_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Study dates label (AR)</label>
                    <input name="study_dates_label_ar" value="{{ old('study_dates_label_ar', $bookingSummaryAr['study_dates_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Duration label (EN)</label>
                    <input name="duration_label" value="{{ old('duration_label', $bookingSummary['duration_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Duration label (AR)</label>
                    <input name="duration_label_ar" value="{{ old('duration_label_ar', $bookingSummaryAr['duration_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Coupon label (EN)</label>
                    <input name="coupon_label" value="{{ old('coupon_label', $bookingSummary['coupon_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Coupon label (AR)</label>
                    <input name="coupon_label_ar" value="{{ old('coupon_label_ar', $bookingSummaryAr['coupon_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Coupon placeholder (EN)</label>
                    <input name="coupon_placeholder" value="{{ old('coupon_placeholder', $bookingSummary['coupon_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Coupon placeholder (AR)</label>
                    <input name="coupon_placeholder_ar" value="{{ old('coupon_placeholder_ar', $bookingSummaryAr['coupon_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Apply button (EN)</label>
                    <input name="coupon_apply_text" value="{{ old('coupon_apply_text', $bookingSummary['coupon_apply_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Apply button (AR)</label>
                    <input name="coupon_apply_text_ar" value="{{ old('coupon_apply_text_ar', $bookingSummaryAr['coupon_apply_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Course summary label (EN)</label>
                    <input name="course_summary_label" value="{{ old('course_summary_label', $bookingSummary['course_summary_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Course summary label (AR)</label>
                    <input name="course_summary_label_ar" value="{{ old('course_summary_label_ar', $bookingSummaryAr['course_summary_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Accommodation summary label (EN)</label>
                    <input name="accommodation_summary_label" value="{{ old('accommodation_summary_label', $bookingSummary['accommodation_summary_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Accommodation summary label (AR)</label>
                    <input name="accommodation_summary_label_ar" value="{{ old('accommodation_summary_label_ar', $bookingSummaryAr['accommodation_summary_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Registration fee label (EN)</label>
                    <input name="registration_fee_label" value="{{ old('registration_fee_label', $bookingSummary['registration_fee_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Registration fee label (AR)</label>
                    <input name="registration_fee_label_ar" value="{{ old('registration_fee_label_ar', $bookingSummaryAr['registration_fee_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Course discount label (EN)</label>
                    <input name="course_discount_label" value="{{ old('course_discount_label', $bookingSummary['course_discount_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Course discount label (AR)</label>
                    <input name="course_discount_label_ar" value="{{ old('course_discount_label_ar', $bookingSummaryAr['course_discount_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Foundation discount label (EN)</label>
                    <input name="foundation_discount_label" value="{{ old('foundation_discount_label', $bookingSummary['foundation_discount_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Foundation discount label (AR)</label>
                    <input name="foundation_discount_label_ar" value="{{ old('foundation_discount_label_ar', $bookingSummaryAr['foundation_discount_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total discount label (EN)</label>
                    <input name="total_discount_label" value="{{ old('total_discount_label', $bookingSummary['total_discount_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Total discount label (AR)</label>
                    <input name="total_discount_label_ar" value="{{ old('total_discount_label_ar', $bookingSummaryAr['total_discount_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm button text (EN)</label>
                    <input name="confirm_button_text" value="{{ old('confirm_button_text', $bookingSummary['confirm_button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirm button text (AR)</label>
                    <input name="confirm_button_text_ar" value="{{ old('confirm_button_text_ar', $bookingSummaryAr['confirm_button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Booking Summary</button>
            </div>
        </form>
    </div>
</div>
@endsection
