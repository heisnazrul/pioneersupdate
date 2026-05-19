@extends('admin.layouts.layout')

@php
    $hero = $hero ?? [];
    $heroAr = $heroAr ?? [];
    $card = $card ?? [];
    $cardAr = $cardAr ?? [];
    $empty = $empty ?? [];
    $emptyAr = $emptyAr ?? [];
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Wishlist</h2>
            <p class="text-sm text-gray-500 mt-1">Static labels only; wishlist items are dynamic.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Header</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.wishlist.hero') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                    <input name="title" value="{{ old('title', $hero['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                    <input name="title_ar" value="{{ old('title_ar', $heroAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subtitle (EN)</label>
                    <textarea name="subtitle" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('subtitle', $hero['subtitle'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subtitle (AR)</label>
                    <textarea name="subtitle_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('subtitle_ar', $heroAr['subtitle'] ?? '') }}</textarea>
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Header</button>
            </div>
        </form>
    </div>

    {{-- CARD LABELS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Card Labels</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.wishlist.card') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type suffix (EN)</label>
                    <input name="type_suffix" value="{{ old('type_suffix', $card['type_suffix'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type suffix (AR)</label>
                    <input name="type_suffix_ar" value="{{ old('type_suffix_ar', $cardAr['type_suffix'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View details (EN)</label>
                    <input name="view_details_text" value="{{ old('view_details_text', $card['view_details_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View details (AR)</label>
                    <input name="view_details_text_ar" value="{{ old('view_details_text_ar', $cardAr['view_details_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Card Labels</button>
            </div>
        </form>
    </div>

    {{-- EMPTY STATE --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Empty State</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.wishlist.empty') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                    <input name="title" value="{{ old('title', $empty['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                    <input name="title_ar" value="{{ old('title_ar', $emptyAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subtitle (EN)</label>
                    <textarea name="subtitle" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('subtitle', $empty['subtitle'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subtitle (AR)</label>
                    <textarea name="subtitle_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('subtitle_ar', $emptyAr['subtitle'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA text (EN)</label>
                    <input name="cta_text" value="{{ old('cta_text', $empty['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA text (AR)</label>
                    <input name="cta_text_ar" value="{{ old('cta_text_ar', $emptyAr['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA URL</label>
                    <input name="cta_url" value="{{ old('cta_url', $empty['cta_url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Empty State</button>
            </div>
        </form>
    </div>
</div>
@endsection
