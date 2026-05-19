@extends('admin.layouts.layout')

@php
    $hero = $hero ?? [];
    $heroAr = $heroAr ?? [];
    $empty = $empty ?? [];
    $emptyAr = $emptyAr ?? [];
    $card = $card ?? [];
    $cardAr = $cardAr ?? [];
    $sidebar = $sidebar ?? [];
    $sidebarAr = $sidebarAr ?? [];
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Articles Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static text only; article cards remain dynamic.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Hero</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.articles.hero') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $hero['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $heroAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (EN)</label>
                    <textarea name="subheading" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('subheading', $hero['subheading'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (AR)</label>
                    <textarea name="subheading_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('subheading_ar', $heroAr['subheading'] ?? '') }}</textarea>
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Hero</button>
            </div>
        </form>
    </div>

    {{-- EMPTY STATE --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Empty State</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.articles.empty') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $empty['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $emptyAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Message (EN)</label>
                    <textarea name="message" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('message', $empty['message'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Message (AR)</label>
                    <textarea name="message_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('message_ar', $emptyAr['message'] ?? '') }}</textarea>
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Empty State</button>
            </div>
        </form>
    </div>

    {{-- CARD LABELS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Card Labels</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.articles.card') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Read more (EN)</label>
                    <input name="read_more_label" value="{{ old('read_more_label', $card['read_more_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Read more (AR)</label>
                    <input name="read_more_label_ar" value="{{ old('read_more_label_ar', $cardAr['read_more_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category fallback (EN)</label>
                    <input name="category_fallback" value="{{ old('category_fallback', $card['category_fallback'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category fallback (AR)</label>
                    <input name="category_fallback_ar" value="{{ old('category_fallback_ar', $cardAr['category_fallback'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Card Labels</button>
            </div>
        </form>
    </div>

    {{-- SIDEBAR --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Sidebar</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.articles.sidebar') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Recent title (EN)</label>
                    <input name="recent_title" value="{{ old('recent_title', $sidebar['recent_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Recent title (AR)</label>
                    <input name="recent_title_ar" value="{{ old('recent_title_ar', $sidebarAr['recent_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Recent badge (EN)</label>
                    <input name="recent_badge" value="{{ old('recent_badge', $sidebar['recent_badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Recent badge (AR)</label>
                    <input name="recent_badge_ar" value="{{ old('recent_badge_ar', $sidebarAr['recent_badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Categories title (EN)</label>
                    <input name="categories_title" value="{{ old('categories_title', $sidebar['categories_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Categories title (AR)</label>
                    <input name="categories_title_ar" value="{{ old('categories_title_ar', $sidebarAr['categories_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Sidebar</button>
            </div>
        </form>
    </div>
</div>
@endsection
