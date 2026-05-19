@extends('admin.layouts.layout')

@php
    $statsItems = $stats['items'] ?? [];
    $statsItemsAr = $statsAr['items'] ?? [];
    $trustItems = $trust['items'] ?? [];
    $trustItemsAr = $trustAr['items'] ?? [];
    $statsCount = max(count($statsItems), 2);
    $trustCount = max(count($trustItems), 5);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / University</p>
            <h2 class="text-2xl font-bold">Home Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static texts and image URLs for homepage sections.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('admin.cms.university.home.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Hero</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="text-sm font-medium">Headline (EN)</label><input name="hero[headline]" value="{{ old('hero.headline', $hero['headline'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Headline (AR)</label><input name="hero[headline_ar]" value="{{ old('hero.headline_ar', $heroAr['headline'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Subheadline (EN)</label><textarea name="hero[subheadline]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('hero.subheadline', $hero['subheadline'] ?? '') }}</textarea></div>
                    <div><label class="text-sm font-medium">Subheadline (AR)</label><textarea name="hero[subheadline_ar]" rows="2" dir="rtl" class="mt-1 w-full border rounded px-3 py-2">{{ old('hero.subheadline_ar', $heroAr['subheadline'] ?? '') }}</textarea></div>
                    <div><label class="text-sm font-medium">Background Image URL</label><input name="hero[background_image]" value="{{ old('hero.background_image', $hero['background_image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Figure Image URL</label><input name="hero[figure_image]" value="{{ old('hero.figure_image', $hero['figure_image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Search Label (EN)</label><input name="hero[search_label]" value="{{ old('hero.search_label', $hero['search_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Search Label (AR)</label><input name="hero[search_label_ar]" value="{{ old('hero.search_label_ar', $heroAr['search_label'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Courses Placeholder (EN)</label><input name="hero[search_placeholder_courses]" value="{{ old('hero.search_placeholder_courses', $hero['search_placeholder_courses'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Courses Placeholder (AR)</label><input name="hero[search_placeholder_courses_ar]" value="{{ old('hero.search_placeholder_courses_ar', $heroAr['search_placeholder_courses'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Universities Placeholder (EN)</label><input name="hero[search_placeholder_universities]" value="{{ old('hero.search_placeholder_universities', $hero['search_placeholder_universities'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Universities Placeholder (AR)</label><input name="hero[search_placeholder_universities_ar]" value="{{ old('hero.search_placeholder_universities_ar', $heroAr['search_placeholder_universities'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Country Label/Placeholder (EN)</label><input name="hero[country_label]" value="{{ old('hero.country_label', $hero['country_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"><input name="hero[country_placeholder]" value="{{ old('hero.country_placeholder', $hero['country_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder"></div>
                    <div><label class="text-sm font-medium">Country Label/Placeholder (AR)</label><input name="hero[country_label_ar]" value="{{ old('hero.country_label_ar', $heroAr['country_label'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"><input name="hero[country_placeholder_ar]" value="{{ old('hero.country_placeholder_ar', $heroAr['country_placeholder'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder"></div>
                    <div><label class="text-sm font-medium">Level Label/Placeholder (EN)</label><input name="hero[level_label]" value="{{ old('hero.level_label', $hero['level_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"><input name="hero[level_placeholder]" value="{{ old('hero.level_placeholder', $hero['level_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder"></div>
                    <div><label class="text-sm font-medium">Level Label/Placeholder (AR)</label><input name="hero[level_label_ar]" value="{{ old('hero.level_label_ar', $heroAr['level_label'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"><input name="hero[level_placeholder_ar]" value="{{ old('hero.level_placeholder_ar', $heroAr['level_placeholder'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder"></div>
                    <div><label class="text-sm font-medium">Intake Label/Placeholder (EN)</label><input name="hero[intake_label]" value="{{ old('hero.intake_label', $hero['intake_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"><input name="hero[intake_placeholder]" value="{{ old('hero.intake_placeholder', $hero['intake_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder"></div>
                    <div><label class="text-sm font-medium">Intake Label/Placeholder (AR)</label><input name="hero[intake_label_ar]" value="{{ old('hero.intake_label_ar', $heroAr['intake_label'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"><input name="hero[intake_placeholder_ar]" value="{{ old('hero.intake_placeholder_ar', $heroAr['intake_placeholder'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder"></div>
                    <div><label class="text-sm font-medium">Tabs (EN)</label><input name="hero[tab_courses]" value="{{ old('hero.tab_courses', $hero['tab_courses'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"><input name="hero[tab_universities]" value="{{ old('hero.tab_universities', $hero['tab_universities'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Universities"></div>
                    <div><label class="text-sm font-medium">Tabs (AR)</label><input name="hero[tab_courses_ar]" value="{{ old('hero.tab_courses_ar', $heroAr['tab_courses'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"><input name="hero[tab_universities_ar]" value="{{ old('hero.tab_universities_ar', $heroAr['tab_universities'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Universities"></div>
                    <div><label class="text-sm font-medium">Search Button (EN)</label><input name="hero[search_button_text]" value="{{ old('hero.search_button_text', $hero['search_button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Search Button (AR)</label><input name="hero[search_button_text_ar]" value="{{ old('hero.search_button_text_ar', $heroAr['search_button_text'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"></div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Stats</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div><label class="text-sm font-medium">Heading (EN)</label><input name="stats[heading]" value="{{ old('stats.heading', $stats['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Heading (AR)</label><input name="stats[heading_ar]" value="{{ old('stats.heading_ar', $statsAr['heading'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Body (EN)</label><textarea name="stats[body]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('stats.body', $stats['body'] ?? '') }}</textarea></div>
                    <div><label class="text-sm font-medium">Body (AR)</label><textarea name="stats[body_ar]" rows="2" dir="rtl" class="mt-1 w-full border rounded px-3 py-2">{{ old('stats.body_ar', $statsAr['body'] ?? '') }}</textarea></div>
                    <div><label class="text-sm font-medium">Mobile Heading (EN)</label><input name="stats[mobile_heading]" value="{{ old('stats.mobile_heading', $stats['mobile_heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Mobile Heading (AR)</label><input name="stats[mobile_heading_ar]" value="{{ old('stats.mobile_heading_ar', $statsAr['mobile_heading'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $statsCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Value</label>
                            <input name="stats[items][{{ $i }}][value]" value="{{ old("stats.items.$i.value", $statsItems[$i]['value'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Label (EN)</label>
                            <input name="stats[items][{{ $i }}][label]" value="{{ old("stats.items.$i.label", $statsItems[$i]['label'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Label (AR)</label>
                            <input name="stats[items][{{ $i }}][label_ar]" value="{{ old("stats.items.$i.label_ar", $statsItemsAr[$i]['label'] ?? '') }}" dir="rtl" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Section Texts</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Certificates Heading/Body (EN)</label>
                        <input name="certificates[heading]" value="{{ old('certificates.heading', $certificates['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <textarea name="certificates[body]" rows="2" class="mt-2 w-full border rounded px-3 py-2">{{ old('certificates.body', $certificates['body'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Certificates Heading/Body (AR)</label>
                        <input name="certificates[heading_ar]" value="{{ old('certificates.heading_ar', $certificatesAr['heading'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2">
                        <textarea name="certificates[body_ar]" rows="2" dir="rtl" class="mt-2 w-full border rounded px-3 py-2">{{ old('certificates.body_ar', $certificatesAr['body'] ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Destinations (EN)</label>
                        <input name="destinations[title]" value="{{ old('destinations.title', $destinations['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="Title">
                        <input name="destinations[subtitle]" value="{{ old('destinations.subtitle', $destinations['subtitle'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Subtitle">
                        <input name="destinations[view_all]" value="{{ old('destinations.view_all', $destinations['view_all'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="View all text">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Destinations (AR)</label>
                        <input name="destinations[title_ar]" value="{{ old('destinations.title_ar', $destinationsAr['title'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2" placeholder="Title">
                        <input name="destinations[subtitle_ar]" value="{{ old('destinations.subtitle_ar', $destinationsAr['subtitle'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Subtitle">
                        <input name="destinations[view_all_ar]" value="{{ old('destinations.view_all_ar', $destinationsAr['view_all'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="View all text">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Universities (EN)</label>
                        <input name="universities[title]" value="{{ old('universities.title', $universities['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="Title">
                        <input name="universities[subtitle]" value="{{ old('universities.subtitle', $universities['subtitle'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Subtitle">
                        <input name="universities[view_all]" value="{{ old('universities.view_all', $universities['view_all'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="View all">
                        <input name="universities[browse_all]" value="{{ old('universities.browse_all', $universities['browse_all'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Browse all">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Universities (AR)</label>
                        <input name="universities[title_ar]" value="{{ old('universities.title_ar', $universitiesAr['title'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2" placeholder="Title">
                        <input name="universities[subtitle_ar]" value="{{ old('universities.subtitle_ar', $universitiesAr['subtitle'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Subtitle">
                        <input name="universities[view_all_ar]" value="{{ old('universities.view_all_ar', $universitiesAr['view_all'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="View all">
                        <input name="universities[browse_all_ar]" value="{{ old('universities.browse_all_ar', $universitiesAr['browse_all'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Browse all">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Reviews (EN)</label>
                        <input name="reviews[video_title]" value="{{ old('reviews.video_title', $reviews['video_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="Video title">
                        <input name="reviews[video_subtitle]" value="{{ old('reviews.video_subtitle', $reviews['video_subtitle'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Video subtitle">
                        <input name="reviews[text_title]" value="{{ old('reviews.text_title', $reviews['text_title'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Text title">
                        <input name="reviews[text_subtitle]" value="{{ old('reviews.text_subtitle', $reviews['text_subtitle'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Text subtitle">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Reviews (AR)</label>
                        <input name="reviews[video_title_ar]" value="{{ old('reviews.video_title_ar', $reviewsAr['video_title'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2" placeholder="Video title">
                        <input name="reviews[video_subtitle_ar]" value="{{ old('reviews.video_subtitle_ar', $reviewsAr['video_subtitle'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Video subtitle">
                        <input name="reviews[text_title_ar]" value="{{ old('reviews.text_title_ar', $reviewsAr['text_title'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Text title">
                        <input name="reviews[text_subtitle_ar]" value="{{ old('reviews.text_subtitle_ar', $reviewsAr['text_subtitle'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Text subtitle">
                    </div>

                    <div>
                        <label class="text-sm font-medium">Scholarships (EN)</label>
                        <input name="scholarships[title]" value="{{ old('scholarships.title', $scholarships['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="Title">
                        <input name="scholarships[subtitle]" value="{{ old('scholarships.subtitle', $scholarships['subtitle'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Subtitle">
                        <input name="scholarships[view_all]" value="{{ old('scholarships.view_all', $scholarships['view_all'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="View all">
                        <input name="scholarships[check_eligibility]" value="{{ old('scholarships.check_eligibility', $scholarships['check_eligibility'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Check eligibility">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Scholarships (AR)</label>
                        <input name="scholarships[title_ar]" value="{{ old('scholarships.title_ar', $scholarshipsAr['title'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2" placeholder="Title">
                        <input name="scholarships[subtitle_ar]" value="{{ old('scholarships.subtitle_ar', $scholarshipsAr['subtitle'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Subtitle">
                        <input name="scholarships[view_all_ar]" value="{{ old('scholarships.view_all_ar', $scholarshipsAr['view_all'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="View all">
                        <input name="scholarships[check_eligibility_ar]" value="{{ old('scholarships.check_eligibility_ar', $scholarshipsAr['check_eligibility'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Check eligibility">
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Trust Cards + FAQ + Blogs</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div><label class="text-sm font-medium">Trust Title/Subtitle (EN)</label><input name="trust[title]" value="{{ old('trust.title', $trust['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2"><input name="trust[subtitle]" value="{{ old('trust.subtitle', $trust['subtitle'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2"></div>
                    <div><label class="text-sm font-medium">Trust Title/Subtitle (AR)</label><input name="trust[title_ar]" value="{{ old('trust.title_ar', $trustAr['title'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2"><input name="trust[subtitle_ar]" value="{{ old('trust.subtitle_ar', $trustAr['subtitle'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                    @for($i = 0; $i < $trustCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="trust[items][{{ $i }}][title]" value="{{ old("trust.items.$i.title", $trustItems[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="trust[items][{{ $i }}][title_ar]" value="{{ old("trust.items.$i.title_ar", $trustItemsAr[$i]['title'] ?? '') }}" dir="rtl" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="trust[items][{{ $i }}][description]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("trust.items.$i.description", $trustItems[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="trust[items][{{ $i }}][description_ar]" rows="2" dir="rtl" class="w-full border rounded px-2 py-1 mb-1">{{ old("trust.items.$i.description_ar", $trustItemsAr[$i]['description'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Icon</label>
                            <input name="trust[items][{{ $i }}][icon]" value="{{ old("trust.items.$i.icon", $trustItems[$i]['icon'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">FAQ (EN)</label>
                        <input name="faq[heading]" value="{{ old('faq.heading', $faq['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="Heading">
                        <textarea name="faq[subheading]" rows="2" class="mt-2 w-full border rounded px-3 py-2" placeholder="Subheading">{{ old('faq.subheading', $faq['subheading'] ?? '') }}</textarea>
                        <input name="faq[cta_title]" value="{{ old('faq.cta_title', $faq['cta_title'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="CTA title">
                        <input name="faq[cta_text]" value="{{ old('faq.cta_text', $faq['cta_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="CTA text">
                        <input name="faq[show_more]" value="{{ old('faq.show_more', $faq['show_more'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Show more">
                    </div>
                    <div>
                        <label class="text-sm font-medium">FAQ (AR)</label>
                        <input name="faq[heading_ar]" value="{{ old('faq.heading_ar', $faqAr['heading'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2" placeholder="Heading">
                        <textarea name="faq[subheading_ar]" rows="2" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Subheading">{{ old('faq.subheading_ar', $faqAr['subheading'] ?? '') }}</textarea>
                        <input name="faq[cta_title_ar]" value="{{ old('faq.cta_title_ar', $faqAr['cta_title'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="CTA title">
                        <input name="faq[cta_text_ar]" value="{{ old('faq.cta_text_ar', $faqAr['cta_text'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="CTA text">
                        <input name="faq[show_more_ar]" value="{{ old('faq.show_more_ar', $faqAr['show_more'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Show more">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Blogs (EN)</label>
                        <input name="blogs[heading]" value="{{ old('blogs.heading', $blogs['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="Heading">
                        <input name="blogs[heading_mobile]" value="{{ old('blogs.heading_mobile', $blogs['heading_mobile'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Mobile heading">
                        <input name="blogs[cta_text]" value="{{ old('blogs.cta_text', $blogs['cta_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="CTA text">
                        <input name="blogs[cta_mobile]" value="{{ old('blogs.cta_mobile', $blogs['cta_mobile'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Mobile CTA">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Blogs (AR)</label>
                        <input name="blogs[heading_ar]" value="{{ old('blogs.heading_ar', $blogsAr['heading'] ?? '') }}" dir="rtl" class="mt-1 w-full border rounded px-3 py-2" placeholder="Heading">
                        <input name="blogs[heading_mobile_ar]" value="{{ old('blogs.heading_mobile_ar', $blogsAr['heading_mobile'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Mobile heading">
                        <input name="blogs[cta_text_ar]" value="{{ old('blogs.cta_text_ar', $blogsAr['cta_text'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="CTA text">
                        <input name="blogs[cta_mobile_ar]" value="{{ old('blogs.cta_mobile_ar', $blogsAr['cta_mobile'] ?? '') }}" dir="rtl" class="mt-2 w-full border rounded px-3 py-2" placeholder="Mobile CTA">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Home Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
