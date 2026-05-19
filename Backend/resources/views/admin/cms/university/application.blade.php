@extends('admin.layouts.layout')

@php
    $serviceItems = $services['items'] ?? [];
    $serviceItemsAr = $servicesAr['items'] ?? [];
    $serviceCount = max(count($serviceItems), 6);
    $steps = $process['steps'] ?? [];
    $stepsAr = $processAr['steps'] ?? [];
    $stepCount = max(count($steps), 5);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / University</p>
            <h2 class="text-2xl font-bold">Application Detail Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static content only.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="POST" action="{{ route('admin.cms.university.application.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Hero</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="hero[title]" value="{{ old('hero.title', $hero['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="hero[title_ar]" value="{{ old('hero.title_ar', $heroAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="hero[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('hero.description', $hero['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="hero[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('hero.description_ar', $heroAr['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Primary Button (EN)</label>
                        <input name="hero[btn1_text]" value="{{ old('hero.btn1_text', $hero['btn1_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="hero[btn1_text_ar]" value="{{ old('hero.btn1_text_ar', $heroAr['btn1_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="hero[btn1_link]" value="{{ old('hero.btn1_link', $hero['btn1_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Secondary Button (EN)</label>
                        <input name="hero[btn2_text]" value="{{ old('hero.btn2_text', $hero['btn2_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="hero[btn2_text_ar]" value="{{ old('hero.btn2_text_ar', $heroAr['btn2_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="hero[btn2_link]" value="{{ old('hero.btn2_link', $hero['btn2_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Services</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (EN)</label>
                        <input name="services[subtitle]" value="{{ old('services.subtitle', $services['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (AR)</label>
                        <input name="services[subtitle_ar]" value="{{ old('services.subtitle_ar', $servicesAr['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="services[title]" value="{{ old('services.title', $services['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="services[title_ar]" value="{{ old('services.title_ar', $servicesAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="services[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('services.description', $services['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="services[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('services.description_ar', $servicesAr['description'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $serviceCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="services[items][{{ $i }}][title]" value="{{ old("services.items.$i.title", $serviceItems[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="services[items][{{ $i }}][title_ar]" value="{{ old("services.items.$i.title_ar", $serviceItemsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="services[items][{{ $i }}][description]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("services.items.$i.description", $serviceItems[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="services[items][{{ $i }}][description_ar]" rows="2" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">{{ old("services.items.$i.description_ar", $serviceItemsAr[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Icon</label>
                            <input name="services[items][{{ $i }}][icon]" value="{{ old("services.items.$i.icon", $serviceItems[$i]['icon'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Process</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (EN)</label>
                        <input name="process[subtitle]" value="{{ old('process.subtitle', $process['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (AR)</label>
                        <input name="process[subtitle_ar]" value="{{ old('process.subtitle_ar', $processAr['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="process[title]" value="{{ old('process.title', $process['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="process[title_ar]" value="{{ old('process.title_ar', $processAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="process[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('process.description', $process['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="process[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('process.description_ar', $processAr['description'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $stepCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Number</label>
                            <input name="process[steps][{{ $i }}][number]" value="{{ old("process.steps.$i.number", $steps[$i]['number'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="process[steps][{{ $i }}][title]" value="{{ old("process.steps.$i.title", $steps[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="process[steps][{{ $i }}][title_ar]" value="{{ old("process.steps.$i.title_ar", $stepsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="process[steps][{{ $i }}][description]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("process.steps.$i.description", $steps[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="process[steps][{{ $i }}][description_ar]" rows="2" class="w-full border rounded px-2 py-1" dir="rtl">{{ old("process.steps.$i.description_ar", $stepsAr[$i]['description'] ?? '') }}</textarea>
                        </div>
                    @endfor
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stat Value</label>
                        <input name="process[stat_value]" value="{{ old('process.stat_value', $process['stat']['value'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Stat Label (EN)</label>
                        <input name="process[stat_label]" value="{{ old('process.stat_label', $process['stat']['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="process[stat_label_ar]" value="{{ old('process.stat_label_ar', $processAr['stat']['label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Stat Description (EN)</label>
                        <textarea name="process[stat_description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('process.stat_description', $process['stat']['description'] ?? '') }}</textarea>
                        <textarea name="process[stat_description_ar]" rows="2" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">{{ old('process.stat_description_ar', $processAr['stat']['description'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">CTA</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="cta[title]" value="{{ old('cta.title', $cta['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="cta[title_ar]" value="{{ old('cta.title_ar', $ctaAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="cta[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('cta.description', $cta['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="cta[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('cta.description_ar', $ctaAr['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Button (EN)</label>
                        <input name="cta[btn_text]" value="{{ old('cta.btn_text', $cta['btn_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="cta[btn_text_ar]" value="{{ old('cta.btn_text_ar', $ctaAr['btn_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="cta[btn_link]" value="{{ old('cta.btn_link', $cta['btn_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Application Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
