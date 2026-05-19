@extends('admin.layouts.layout')

@php
    $sections = $sections ?? [];
    $sectionsAr = $sectionsAr ?? [];
    $count = max(count($sections), 4);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Offers Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static text only; offer cards remain dynamic.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Hero</h3>
            <span class="text-xs text-gray-500">Badge, heading, subheading</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.offers.update') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Badge (EN)</label>
                    <input name="badge" value="{{ old('badge', $hero['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Badge (AR)</label>
                    <input name="badge_ar" value="{{ old('badge_ar', $heroAr['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Headline (EN)</label>
                    <input name="headline" value="{{ old('headline', $hero['headline'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Headline (AR)</label>
                    <input name="headline_ar" value="{{ old('headline_ar', $heroAr['headline'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheadline (EN)</label>
                    <textarea name="subheadline" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('subheadline', $hero['subheadline'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheadline (AR)</label>
                    <textarea name="subheadline_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('subheadline_ar', $heroAr['subheadline'] ?? '') }}</textarea>
                </div>
            </div>

            <div class="border border-gray-100 rounded-lg p-3">
                <div class="flex items-center justify-between mb-2">
                    <h4 class="font-semibold">Sections</h4>
                    <span class="text-xs text-gray-500">Titles, subtitles, links only</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    @for($i=0; $i < $count; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Title (EN)</label>
                            <input name="sections[{{ $i }}][title]" value="{{ old("sections.$i.title", $sections[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Title (AR)</label>
                            <input name="sections[{{ $i }}][title_ar]" value="{{ old("sections.$i.title_ar", $sectionsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-2" dir="rtl">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Subtitle (EN)</label>
                            <input name="sections[{{ $i }}][subtitle]" value="{{ old("sections.$i.subtitle", $sections[$i]['subtitle'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Subtitle (AR)</label>
                            <input name="sections[{{ $i }}][subtitle_ar]" value="{{ old("sections.$i.subtitle_ar", $sectionsAr[$i]['subtitle'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-2" dir="rtl">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">View all URL</label>
                            <input name="sections[{{ $i }}][link]" value="{{ old("sections.$i.link", $sections[$i]['link'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Offers</button>
            </div>
        </form>
    </div>
</div>
@endsection
