@extends('admin.layouts.layout')

@php
    $content = $content ?? [];
    $arContent = $arContent ?? [];
    $whyItems = $content['why']['items'] ?? [];
    $whyItemsAr = $arContent['why']['items'] ?? [];
    $itemCount = max(count($whyItems), count($whyItemsAr), 3);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">About Us Page</h2>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('admin.cms.course-english.about.update') }}" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold mb-3">Breadcrumb + Title</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Home (EN)</label>
                        <input name="breadcrumb[home]" value="{{ old('breadcrumb.home', $content['breadcrumb']['home'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Home (AR)</label>
                        <input name="breadcrumb[home_ar]" value="{{ old('breadcrumb.home_ar', $arContent['breadcrumb']['home'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current (EN)</label>
                        <input name="breadcrumb[current]" value="{{ old('breadcrumb.current', $content['breadcrumb']['current'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current (AR)</label>
                        <input name="breadcrumb[current_ar]" value="{{ old('breadcrumb.current_ar', $arContent['breadcrumb']['current'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Page Title (EN)</label>
                        <input name="page_title" value="{{ old('page_title', $content['page_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Page Title (AR)</label>
                        <input name="page_title_ar" value="{{ old('page_title_ar', $arContent['page_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold mb-3">Intro</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="intro[title]" value="{{ old('intro.title', $content['intro']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="intro[title_ar]" value="{{ old('intro.title_ar', $arContent['intro']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Paragraph 1 (EN)</label>
                        <textarea name="intro[paragraph_1]" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('intro.paragraph_1', $content['intro']['paragraph_1'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Paragraph 1 (AR)</label>
                        <textarea name="intro[paragraph_1_ar]" rows="3" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('intro.paragraph_1_ar', $arContent['intro']['paragraph_1'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Paragraph 2 (EN)</label>
                        <textarea name="intro[paragraph_2]" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('intro.paragraph_2', $content['intro']['paragraph_2'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Paragraph 2 (AR)</label>
                        <textarea name="intro[paragraph_2_ar]" rows="3" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('intro.paragraph_2_ar', $arContent['intro']['paragraph_2'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image URL</label>
                        <input name="intro[image]" value="{{ old('intro.image', $content['intro']['image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Upload Image</label>
                        <input type="file" name="intro[image_file]" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold mb-3">Vision + Mission</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vision Title (EN)</label>
                        <input name="vision[title]" value="{{ old('vision.title', $content['vision']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vision Title (AR)</label>
                        <input name="vision[title_ar]" value="{{ old('vision.title_ar', $arContent['vision']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vision Body (EN)</label>
                        <textarea name="vision[body]" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('vision.body', $content['vision']['body'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vision Body (AR)</label>
                        <textarea name="vision[body_ar]" rows="3" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('vision.body_ar', $arContent['vision']['body'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mission Title (EN)</label>
                        <input name="mission[title]" value="{{ old('mission.title', $content['mission']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mission Title (AR)</label>
                        <input name="mission[title_ar]" value="{{ old('mission.title_ar', $arContent['mission']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mission Body (EN)</label>
                        <textarea name="mission[body]" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('mission.body', $content['mission']['body'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mission Body (AR)</label>
                        <textarea name="mission[body_ar]" rows="3" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('mission.body_ar', $arContent['mission']['body'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-4">
                <h3 class="font-semibold mb-3">Why Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Section Title (EN)</label>
                        <input name="why[title]" value="{{ old('why.title', $content['why']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Section Title (AR)</label>
                        <input name="why[title_ar]" value="{{ old('why.title_ar', $arContent['why']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (EN)</label>
                        <input name="why[subtitle]" value="{{ old('why.subtitle', $content['why']['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (AR)</label>
                        <input name="why[subtitle_ar]" value="{{ old('why.subtitle_ar', $arContent['why']['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                </div>
                <div class="space-y-4">
                    @for($i = 0; $i < $itemCount; $i++)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border rounded p-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Item {{ $i + 1 }} Title (EN)</label>
                                <input name="why[items][{{ $i }}][title]" value="{{ old("why.items.$i.title", $whyItems[$i]['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Item {{ $i + 1 }} Title (AR)</label>
                                <input name="why[items][{{ $i }}][title_ar]" value="{{ old("why.items.$i.title_ar", $whyItemsAr[$i]['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Item {{ $i + 1 }} Body (EN)</label>
                                <textarea name="why[items][{{ $i }}][body]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old("why.items.$i.body", $whyItems[$i]['body'] ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Item {{ $i + 1 }} Body (AR)</label>
                                <textarea name="why[items][{{ $i }}][body_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old("why.items.$i.body_ar", $whyItemsAr[$i]['body'] ?? '') }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Item {{ $i + 1 }} Icon Path (same for EN/AR)</label>
                                <input name="why[items][{{ $i }}][icon]" value="{{ old("why.items.$i.icon", $whyItems[$i]['icon'] ?? $whyItemsAr[$i]['icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save About Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
