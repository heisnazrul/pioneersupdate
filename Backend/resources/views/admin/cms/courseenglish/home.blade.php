@extends('admin.layouts.layout')

@php
    $heroServices = array_values($hero['services'] ?? []);
    $heroServicesAr = array_values($heroAr['services'] ?? []);
    $serviceCount = max(count($heroServices), count($heroServicesAr), 3);

    $statItems = $stats['items'] ?? ($stats ?: []);
    $statItemsAr = $statsAr['items'] ?? ($statsAr ?: []);
    $statCount = max(count($statItems), 2);

    $statMobile = $statsMobile['items'] ?? ($statsMobile ?: []);
    $statMobileAr = $statsMobileAr['items'] ?? ($statsMobileAr ?: []);
    $statMobileCount = max(count($statMobile), 2);

    $links = $destinations['links'] ?? [];
    $linksAr = $destinationsAr['links'] ?? [];
    $destCount = max(count($links), count($linksAr), 20);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Home Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static text, buttons, and icons only. Dynamic data (schools, courses, certificates, etc.) comes from APIs.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Hero</h3>
            <span class="text-xs text-gray-500">Text, placeholders, services, images</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.hero') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Promo text</label>
                    <input name="promo_text" value="{{ old('promo_text', $hero['promo_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Promo text (AR)</label>
                    <input name="ar_promo_text" value="{{ old('ar_promo_text', $heroAr['promo_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Promo icon URL</label>
                    <input name="promo_icon" value="{{ old('promo_icon', $hero['promo_icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="promo_icon_file" class="mt-2 w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search button text</label>
                    <input name="search_button_text" value="{{ old('search_button_text', $hero['search_button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search button text (AR)</label>
                    <input name="ar_search_button_text" value="{{ old('ar_search_button_text', $heroAr['search_button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Headline (EN)</label>
                    <input name="headline" value="{{ old('headline', $hero['headline'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Headline (AR)</label>
                    <input name="ar_headline" value="{{ old('ar_headline', $heroAr['headline'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheadline (EN)</label>
                    <textarea name="subheadline" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('subheadline', $hero['subheadline'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheadline (AR)</label>
                    <textarea name="ar_subheadline" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('ar_subheadline', $heroAr['subheadline'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Promo line (EN)</label>
                    <textarea name="promo" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('promo', $hero['promo'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Promo line (AR)</label>
                    <textarea name="ar_promo" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('ar_promo', $heroAr['promo'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Button text (EN)</label>
                    <input name="button_text" value="{{ old('button_text', $hero['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Button text (AR)</label>
                    <input name="button_text_ar" value="{{ old('button_text_ar', $heroAr['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Background image URL</label>
                    <input name="background_image" value="{{ old('background_image', $hero['background_image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="background_image_file" class="mt-2 w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Figure image URL</label>
                    <input name="figure_image" value="{{ old('figure_image', $hero['figure_image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="figure_image_file" class="mt-2 w-full text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search placeholder</label>
                    <input name="search_placeholder" value="{{ old('search_placeholder', $hero['search_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="search_subtext" value="{{ old('search_subtext', $hero['search_subtext'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Sub text (optional)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search placeholder (AR)</label>
                    <input name="ar_search_placeholder" value="{{ old('ar_search_placeholder', $heroAr['search_placeholder'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    <input name="ar_search_subtext" value="{{ old('ar_search_subtext', $heroAr['search_subtext'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Sub text (AR)" dir="rtl">
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course label</label>
                        <input name="course_label" value="{{ old('course_label', $hero['course_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="course_placeholder" value="{{ old('course_placeholder', $hero['course_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Weeks label</label>
                        <input name="weeks_label" value="{{ old('weeks_label', $hero['weeks_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="weeks_placeholder" value="{{ old('weeks_placeholder', $hero['weeks_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start label</label>
                        <input name="start_label" value="{{ old('start_label', $hero['start_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        <input name="start_placeholder" value="{{ old('start_placeholder', $hero['start_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Course label (AR)</label>
                        <input name="ar_course_label" value="{{ old('ar_course_label', $heroAr['course_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        <input name="ar_course_placeholder" value="{{ old('ar_course_placeholder', $heroAr['course_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder (AR)" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Weeks label (AR)</label>
                        <input name="ar_weeks_label" value="{{ old('ar_weeks_label', $heroAr['weeks_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        <input name="ar_weeks_placeholder" value="{{ old('ar_weeks_placeholder', $heroAr['weeks_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder (AR)" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start label (AR)</label>
                        <input name="ar_start_label" value="{{ old('ar_start_label', $heroAr['start_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        <input name="ar_start_placeholder" value="{{ old('ar_start_placeholder', $heroAr['start_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder (AR)" dir="rtl">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Services (one per line)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-2">
                        <p class="text-xs text-gray-500">EN</p>
                        @for($i = 0; $i < $serviceCount; $i++)
                            <input name="services[{{ $i }}]" value="{{ old("services.$i", $heroServices[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Service {{ $i+1 }}">
                        @endfor
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs text-gray-500">AR</p>
                        @for($i = 0; $i < $serviceCount; $i++)
                            <input name="ar_services[{{ $i }}]" value="{{ old("ar_services.$i", $heroServicesAr[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Service {{ $i+1 }}" dir="rtl">
                        @endfor
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Hero</button>
            </div>
        </form>
    </div>

    {{-- STATS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Stats</h3>
            <span class="text-xs text-gray-500">Heading, body, numbers</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.stats') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $stats['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $statsAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Body (EN)</label>
                    <textarea name="body" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('body', $stats['body'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Body (AR)</label>
                    <textarea name="body_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('body_ar', $statsAr['body'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="space-y-3">
                @for($i = 0; $i < $statCount; $i++)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 border border-gray-100 rounded-lg p-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Value</label>
                            <input name="stats[{{ $i }}][value]" value="{{ old("stats.$i.value", $statItems[$i]['value'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Label (EN)</label>
                            <input name="stats[{{ $i }}][label]" value="{{ old("stats.$i.label", $statItems[$i]['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Label (AR)</label>
                            <input name="stats[{{ $i }}][ar_label]" value="{{ old("stats.$i.ar_label", $statItemsAr[$i]['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        </div>
                    </div>
                @endfor
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Stats</button>
            </div>
        </form>
    </div>

    {{-- STATS (MOBILE OVERRIDE) --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Stats (Mobile override)</h3>
            <span class="text-xs text-gray-500">Optional shorter copy for mobile cards</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.stats') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading_mobile" value="{{ old('heading_mobile', $statsMobile['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_mobile_ar" value="{{ old('heading_mobile_ar', $statsMobileAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Body (EN)</label>
                    <textarea name="body_mobile" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('body_mobile', $statsMobile['body'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Body (AR)</label>
                    <textarea name="body_mobile_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('body_mobile_ar', $statsMobileAr['body'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="space-y-3">
                @for($i = 0; $i < $statMobileCount; $i++)
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 border border-gray-100 rounded-lg p-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Value</label>
                            <input name="stats_mobile[{{ $i }}][value]" value="{{ old('stats_mobile.'.$i.'.value', $statMobile[$i]['value'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Label (EN)</label>
                            <input name="stats_mobile[{{ $i }}][label]" value="{{ old('stats_mobile.'.$i.'.label', $statMobile[$i]['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Label (AR)</label>
                            <input name="stats_mobile[{{ $i }}][ar_label]" value="{{ old('stats_mobile.'.$i.'.ar_label', $statMobileAr[$i]['label'] ?? $statMobile[$i]['ar_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        </div>
                    </div>
                @endfor
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Stats (Mobile)</button>
            </div>
        </form>
    </div>

    {{-- CERTIFICATES (text only) --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Certificates (text only)</h3>
            <span class="text-xs text-gray-500">Cards stay dynamic; only heading/subheading editable</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.certificates') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $certMeta['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $certMetaAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (EN)</label>
                    <textarea name="subheading" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('subheading', $certMeta['subheading'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (AR)</label>
                    <textarea name="subheading_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('subheading_ar', $certMetaAr['subheading'] ?? '') }}</textarea>
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Certificates Text</button>
            </div>
        </form>
    </div>

    {{-- PARTNER INSTITUTES --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Partner institutes</h3>
            <span class="text-xs text-gray-500">Heading + buttons; cards remain dynamic</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.partners') }}" class="space-y-3" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $partners['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $partnersAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all label (EN)</label>
                    <input name="view_all_label" value="{{ old('view_all_label', $partners['view_all_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all label (AR)</label>
                    <input name="view_all_label_ar" value="{{ old('view_all_label_ar', $partnersAr['view_all_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all URL</label>
                    <input name="view_all_url" value="{{ old('view_all_url', $partners['view_all_url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Arrow left icon URL</label>
                    <input name="arrow_left_icon" value="{{ old('arrow_left_icon', $partners['arrow_left_icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="arrow_left_icon_file" class="mt-2 w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Arrow right icon URL</label>
                    <input name="arrow_right_icon" value="{{ old('arrow_right_icon', $partners['arrow_right_icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="arrow_right_icon_file" class="mt-2 w-full text-sm">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Partner Section</button>
            </div>
        </form>
    </div>

    {{-- SUMMER PROGRAMS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Summer programs</h3>
            <span class="text-xs text-gray-500">Heading + View all button</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.summer') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $summer['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $summerAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all label (EN)</label>
                    <input name="cta_text" value="{{ old('cta_text', $summer['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all label (AR)</label>
                    <input name="cta_text_ar" value="{{ old('cta_text_ar', $summerAr['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all URL</label>
                    <input name="cta_url" value="{{ old('cta_url', $summer['cta_url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Summer Section</button>
            </div>
        </form>
    </div>

    {{-- ONLINE COURSES --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Online courses</h3>
            <span class="text-xs text-gray-500">Heading + View all button</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.online') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $online['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $onlineAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all label (EN)</label>
                    <input name="cta_text" value="{{ old('cta_text', $online['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all label (AR)</label>
                    <input name="cta_text_ar" value="{{ old('cta_text_ar', $onlineAr['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all URL</label>
                    <input name="cta_url" value="{{ old('cta_url', $online['cta_url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Online Section</button>
            </div>
        </form>
    </div>

    {{-- REVIEWS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Reviews</h3>
            <span class="text-xs text-gray-500">Heading only</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.reviews') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $reviews['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $reviewsAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Reviews</button>
            </div>
        </form>
    </div>

    {{-- DESTINATIONS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Best destinations</h3>
            <span class="text-xs text-gray-500">Heading and links only</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.destinations') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $destinations['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $destinationsAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div class="space-y-2">
                    <p class="text-xs text-gray-500 font-semibold">Links (EN)</p>
                    @for($i=0; $i < $destCount; $i++)
                        <div class="grid grid-cols-3 gap-2">
                            <input name="links[{{ $i }}][label]" value="{{ old("links.$i.label", $links[$i]['label'] ?? '') }}" class="col-span-2 border rounded px-2 py-1" placeholder="Label">
                            <input name="links[{{ $i }}][url]" value="{{ old("links.$i.url", $links[$i]['url'] ?? '') }}" class="col-span-1 border rounded px-2 py-1" placeholder="URL">
                        </div>
                    @endfor
                </div>
                <div class="space-y-2">
                    <p class="text-xs text-gray-500 font-semibold">Links (AR)</p>
                    @for($i=0; $i < $destCount; $i++)
                        <div class="grid grid-cols-3 gap-2">
                            <input name="links_ar[{{ $i }}][label]" value="{{ old("links_ar.$i.label", $linksAr[$i]['label'] ?? '') }}" class="col-span-2 border rounded px-2 py-1" placeholder="Label" dir="rtl">
                            <input name="links_ar[{{ $i }}][url]" value="{{ old("links_ar.$i.url", $linksAr[$i]['url'] ?? '') }}" class="col-span-1 border rounded px-2 py-1" placeholder="URL">
                        </div>
                    @endfor
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Destinations</button>
            </div>
        </form>
    </div>

    {{-- FAQ --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">FAQ</h3>
            <span class="text-xs text-gray-500">Heading, subheading, CTA</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.faq') }}" class="space-y-3" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $faq['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $faqAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (EN)</label>
                    <textarea name="subheading" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('subheading', $faq['subheading'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (AR)</label>
                    <textarea name="subheading_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('subheading_ar', $faqAr['subheading'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA text (EN)</label>
                    <input name="cta_text" value="{{ old('cta_text', $faq['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA text (AR)</label>
                    <input name="cta_text_ar" value="{{ old('cta_text_ar', $faqAr['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA URL</label>
                    <input name="cta_url" value="{{ old('cta_url', $faq['cta_url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA icon URL</label>
                    <input name="cta_icon" value="{{ old('cta_icon', $faq['cta_icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="cta_icon_file" class="mt-2 w-full text-sm">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Help text (EN)</label>
                    <input name="help_text" value="{{ old('help_text', $faq['help_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Help text (AR)</label>
                    <input name="help_text_ar" value="{{ old('help_text_ar', $faqAr['help_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Show more label (EN)</label>
                    <input name="show_more_label" value="{{ old('show_more_label', $faq['show_more_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Show more label (AR)</label>
                    <input name="show_more_label_ar" value="{{ old('show_more_label_ar', $faqAr['show_more_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save FAQ</button>
            </div>
        </form>
    </div>

    {{-- BLOGS --}}
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Blogs</h3>
            <span class="text-xs text-gray-500">Heading + CTA</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.home.blogs') }}" class="space-y-3">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $blogs['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $blogsAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA text (EN)</label>
                    <input name="cta_text" value="{{ old('cta_text', $blogs['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA text (AR)</label>
                    <input name="cta_text_ar" value="{{ old('cta_text_ar', $blogsAr['cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CTA URL</label>
                    <input name="cta_url" value="{{ old('cta_url', $blogs['cta_url'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Blogs</button>
            </div>
        </form>
    </div>
</div>
@endsection
