@extends('admin.layouts.layout')

@php
    $offerItems = $offer['items'] ?? [];
    $offerItemsAr = $offerAr['items'] ?? [];
    $offerCount = max(count($offerItems), 6);
    $processSteps = $process['steps'] ?? [];
    $processStepsAr = $processAr['steps'] ?? [];
    $processCount = max(count($processSteps), 5);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / University</p>
            <h2 class="text-2xl font-bold">Services Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static content only.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="POST" action="{{ route('admin.cms.university.services.update') }}" class="space-y-6">
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
                <h3 class="text-xl font-semibold mb-4">What We Offer</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="what_we_offer[title]" value="{{ old('what_we_offer.title', $offer['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="what_we_offer[title_ar]" value="{{ old('what_we_offer.title_ar', $offerAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="what_we_offer[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('what_we_offer.description', $offer['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="what_we_offer[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('what_we_offer.description_ar', $offerAr['description'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $offerCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="what_we_offer[items][{{ $i }}][title]" value="{{ old("what_we_offer.items.$i.title", $offerItems[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="what_we_offer[items][{{ $i }}][title_ar]" value="{{ old("what_we_offer.items.$i.title_ar", $offerItemsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="what_we_offer[items][{{ $i }}][description]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("what_we_offer.items.$i.description", $offerItems[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="what_we_offer[items][{{ $i }}][description_ar]" rows="2" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">{{ old("what_we_offer.items.$i.description_ar", $offerItemsAr[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Icon</label>
                            <input name="what_we_offer[items][{{ $i }}][icon]" value="{{ old("what_we_offer.items.$i.icon", $offerItems[$i]['icon'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Link</label>
                            <input name="what_we_offer[items][{{ $i }}][link]" value="{{ old("what_we_offer.items.$i.link", $offerItems[$i]['link'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Our Process</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="our_process[title]" value="{{ old('our_process.title', $process['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="our_process[title_ar]" value="{{ old('our_process.title_ar', $processAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="our_process[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('our_process.description', $process['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="our_process[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('our_process.description_ar', $processAr['description'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $processCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Number</label>
                            <input name="our_process[steps][{{ $i }}][number]" value="{{ old("our_process.steps.$i.number", $processSteps[$i]['number'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="our_process[steps][{{ $i }}][title]" value="{{ old("our_process.steps.$i.title", $processSteps[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="our_process[steps][{{ $i }}][title_ar]" value="{{ old("our_process.steps.$i.title_ar", $processStepsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="our_process[steps][{{ $i }}][description]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("our_process.steps.$i.description", $processSteps[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="our_process[steps][{{ $i }}][description_ar]" rows="2" class="w-full border rounded px-2 py-1" dir="rtl">{{ old("our_process.steps.$i.description_ar", $processStepsAr[$i]['description'] ?? '') }}</textarea>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Partner Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Badge (EN)</label>
                        <input name="partner_section[badge]" value="{{ old('partner_section.badge', $partner['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Badge (AR)</label>
                        <input name="partner_section[badge_ar]" value="{{ old('partner_section.badge_ar', $partnerAr['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="partner_section[title]" value="{{ old('partner_section.title', $partner['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="partner_section[title_ar]" value="{{ old('partner_section.title_ar', $partnerAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="partner_section[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('partner_section.description', $partner['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="partner_section[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('partner_section.description_ar', $partnerAr['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Primary Button (EN)</label>
                        <input name="partner_section[button_text]" value="{{ old('partner_section.button_text', $partner['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="partner_section[button_text_ar]" value="{{ old('partner_section.button_text_ar', $partnerAr['button_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="partner_section[button_link]" value="{{ old('partner_section.button_link', $partner['button_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Secondary Button (EN)</label>
                        <input name="partner_section[secondary_button_text]" value="{{ old('partner_section.secondary_button_text', $partner['secondary_button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="partner_section[secondary_button_text_ar]" value="{{ old('partner_section.secondary_button_text_ar', $partnerAr['secondary_button_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="partner_section[secondary_button_link]" value="{{ old('partner_section.secondary_button_link', $partner['secondary_button_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
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
                        <input name="cta[button_text]" value="{{ old('cta.button_text', $cta['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="cta[button_text_ar]" value="{{ old('cta.button_text_ar', $ctaAr['button_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="cta[button_link]" value="{{ old('cta.button_link', $cta['button_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Services Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
