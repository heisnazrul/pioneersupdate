@extends('admin.layouts.layout')

@php
    $catItems = $categories ?? [];
    $catItemsAr = $categoriesAr ?? [];
    $catCount = max(count($catItems), 6);
    $toolItems = $tools['items'] ?? [];
    $toolItemsAr = $toolsAr['items'] ?? [];
    $toolCount = max(count($toolItems), 4);
    $faqItems = $faq['items'] ?? [];
    $faqItemsAr = $faqAr['items'] ?? [];
    $faqCount = max(count($faqItems), 4);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / University</p>
            <h2 class="text-2xl font-bold">Student Guide Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static content only.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="POST" action="{{ route('admin.cms.university.student-guide.update') }}" class="space-y-6">
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
                <h3 class="text-xl font-semibold mb-4">Categories</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $catCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="categories[{{ $i }}][title]" value="{{ old("categories.$i.title", $catItems[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="categories[{{ $i }}][title_ar]" value="{{ old("categories.$i.title_ar", $catItemsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="categories[{{ $i }}][description]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("categories.$i.description", $catItems[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="categories[{{ $i }}][description_ar]" rows="2" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">{{ old("categories.$i.description_ar", $catItemsAr[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Icon</label>
                            <input name="categories[{{ $i }}][icon]" value="{{ old("categories.$i.icon", $catItems[$i]['icon'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Color Class</label>
                            <input name="categories[{{ $i }}][color]" value="{{ old("categories.$i.color", $catItems[$i]['color'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Tools & Resources</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="tools_resources[title]" value="{{ old('tools_resources.title', $tools['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="tools_resources[title_ar]" value="{{ old('tools_resources.title_ar', $toolsAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (EN)</label>
                        <input name="tools_resources[subtitle]" value="{{ old('tools_resources.subtitle', $tools['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (AR)</label>
                        <input name="tools_resources[subtitle_ar]" value="{{ old('tools_resources.subtitle_ar', $toolsAr['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="tools_resources[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('tools_resources.description', $tools['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="tools_resources[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('tools_resources.description_ar', $toolsAr['description'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $toolCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="tools_resources[items][{{ $i }}][title]" value="{{ old("tools_resources.items.$i.title", $toolItems[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="tools_resources[items][{{ $i }}][title_ar]" value="{{ old("tools_resources.items.$i.title_ar", $toolItemsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Type</label>
                            <input name="tools_resources[items][{{ $i }}][type]" value="{{ old("tools_resources.items.$i.type", $toolItems[$i]['type'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Icon</label>
                            <input name="tools_resources[items][{{ $i }}][icon]" value="{{ old("tools_resources.items.$i.icon", $toolItems[$i]['icon'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Link</label>
                            <input name="tools_resources[items][{{ $i }}][link]" value="{{ old("tools_resources.items.$i.link", $toolItems[$i]['link'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">FAQ</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="faq[title]" value="{{ old('faq.title', $faq['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="faq[title_ar]" value="{{ old('faq.title_ar', $faqAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (EN)</label>
                        <input name="faq[subtitle]" value="{{ old('faq.subtitle', $faq['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (AR)</label>
                        <input name="faq[subtitle_ar]" value="{{ old('faq.subtitle_ar', $faqAr['subtitle'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="faq[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('faq.description', $faq['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="faq[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('faq.description_ar', $faqAr['description'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $faqCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Question (EN)</label>
                            <input name="faq[items][{{ $i }}][question]" value="{{ old("faq.items.$i.question", $faqItems[$i]['question'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Question (AR)</label>
                            <input name="faq[items][{{ $i }}][question_ar]" value="{{ old("faq.items.$i.question_ar", $faqItemsAr[$i]['question'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Answer (EN)</label>
                            <textarea name="faq[items][{{ $i }}][answer]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("faq.items.$i.answer", $faqItems[$i]['answer'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Answer (AR)</label>
                            <textarea name="faq[items][{{ $i }}][answer_ar]" rows="2" class="w-full border rounded px-2 py-1" dir="rtl">{{ old("faq.items.$i.answer_ar", $faqItemsAr[$i]['answer'] ?? '') }}</textarea>
                        </div>
                    @endfor
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CTA Title (EN)</label>
                        <input name="faq[cta_title]" value="{{ old('faq.cta_title', $faq['cta']['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="faq[cta_title_ar]" value="{{ old('faq.cta_title_ar', $faqAr['cta']['title'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CTA Description (EN)</label>
                        <textarea name="faq[cta_description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('faq.cta_description', $faq['cta']['description'] ?? '') }}</textarea>
                        <textarea name="faq[cta_description_ar]" rows="2" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">{{ old('faq.cta_description_ar', $faqAr['cta']['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CTA Button (EN)</label>
                        <input name="faq[cta_btn_text]" value="{{ old('faq.cta_btn_text', $faq['cta']['btn_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="faq[cta_btn_text_ar]" value="{{ old('faq.cta_btn_text_ar', $faqAr['cta']['btn_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="faq[cta_btn_link]" value="{{ old('faq.cta_btn_link', $faq['cta']['btn_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Trust Section</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="trust[title]" value="{{ old('trust.title', $trust['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="trust[title_ar]" value="{{ old('trust.title_ar', $trustAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="trust[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('trust.description', $trust['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="trust[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('trust.description_ar', $trustAr['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CTA Text (EN)</label>
                        <input name="trust[cta_text]" value="{{ old('trust.cta_text', $trust['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="trust[cta_text_ar]" value="{{ old('trust.cta_text_ar', $trustAr['cta_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        <input name="trust[cta_link]" value="{{ old('trust.cta_link', $trust['cta_link'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Link">
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Featured Guides Category</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category Slug</label>
                    <input name="featured_guides_category_slug" value="{{ old('featured_guides_category_slug', $featuredSlug ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="featured_guides_category_slug_ar" value="{{ old('featured_guides_category_slug_ar', $featuredSlugAr ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Student Guide Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
