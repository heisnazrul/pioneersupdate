@extends('admin.layouts.layout')

@php
    $reqItems = $requirements['items'] ?? [];
    $reqItemsAr = $requirementsAr['items'] ?? [];
    $reqCount = max(count($reqItems), 5);
    $steps = $process['steps'] ?? [];
    $stepsAr = $processAr['steps'] ?? [];
    $stepCount = max(count($steps), 5);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / University</p>
            <h2 class="text-2xl font-bold">Applications Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static content only.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="POST" action="{{ route('admin.cms.university.applications.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Hero</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Badge (EN)</label>
                        <input name="hero[badge]" value="{{ old('hero.badge', $hero['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Badge (AR)</label>
                        <input name="hero[badge_ar]" value="{{ old('hero.badge_ar', $heroAr['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
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
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Image URL</label>
                        <input name="hero[image]" value="{{ old('hero.image', $hero['image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Requirements</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="requirements[title]" value="{{ old('requirements.title', $requirements['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="requirements[title_ar]" value="{{ old('requirements.title_ar', $requirementsAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $reqCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="requirements[items][{{ $i }}][title]" value="{{ old("requirements.items.$i.title", $reqItems[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="requirements[items][{{ $i }}][title_ar]" value="{{ old("requirements.items.$i.title_ar", $reqItemsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="requirements[items][{{ $i }}][description]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("requirements.items.$i.description", $reqItems[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="requirements[items][{{ $i }}][description_ar]" rows="2" class="w-full border rounded px-2 py-1" dir="rtl">{{ old("requirements.items.$i.description_ar", $reqItemsAr[$i]['description'] ?? '') }}</textarea>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Process Steps</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="process_steps[title]" value="{{ old('process_steps.title', $process['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="process_steps[title_ar]" value="{{ old('process_steps.title_ar', $processAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $stepCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Number</label>
                            <input name="process_steps[steps][{{ $i }}][number]" value="{{ old("process_steps.steps.$i.number", $steps[$i]['number'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="process_steps[steps][{{ $i }}][title]" value="{{ old("process_steps.steps.$i.title", $steps[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="process_steps[steps][{{ $i }}][title_ar]" value="{{ old("process_steps.steps.$i.title_ar", $stepsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="process_steps[steps][{{ $i }}][description]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("process_steps.steps.$i.description", $steps[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="process_steps[steps][{{ $i }}][description_ar]" rows="2" class="w-full border rounded px-2 py-1" dir="rtl">{{ old("process_steps.steps.$i.description_ar", $stepsAr[$i]['description'] ?? '') }}</textarea>
                        </div>
                    @endfor
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
                        <input name="cta[button_text]" value="{{ old('cta.button_text', $cta['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="cta[button_text_ar]" value="{{ old('cta.button_text_ar', $ctaAr['button_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="cta[button_link]" value="{{ old('cta.button_link', $cta['button_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Applications Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
