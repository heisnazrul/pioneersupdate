@extends('admin.layouts.layout')

@php
    $hero = $hero ?? [];
    $heroAr = $heroAr ?? [];
    $table = $table ?? [];
    $tableAr = $tableAr ?? [];
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Compare Page</h2>
            <p class="text-sm text-gray-500 mt-1">Only static labels are editable; comparison data is dynamic.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Header</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.compare.hero') }}" class="space-y-4">
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

    {{-- TABLE LABELS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Table Labels</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.compare.table') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Criteria label (EN)</label>
                    <input name="criteria_label" value="{{ old('criteria_label', $table['criteria_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Criteria label (AR)</label>
                    <input name="criteria_label_ar" value="{{ old('criteria_label_ar', $tableAr['criteria_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price label (EN)</label>
                    <input name="price_label" value="{{ old('price_label', $table['price_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price label (AR)</label>
                    <input name="price_label_ar" value="{{ old('price_label_ar', $tableAr['price_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Action button (EN)</label>
                    <input name="action_button_text" value="{{ old('action_button_text', $table['action_button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Action button (AR)</label>
                    <input name="action_button_text_ar" value="{{ old('action_button_text_ar', $tableAr['action_button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Table Labels</button>
            </div>
        </form>
    </div>
</div>
@endsection
