@extends('admin.layouts.layout')

@php
    $hero = $hero ?? [];
    $heroAr = $heroAr ?? [];
    $cta = $cta ?? [];
    $ctaAr = $ctaAr ?? [];
    $features = $features['items'] ?? [];
    $featuresAr = $featuresAr['items'] ?? [];
    $destinations = $destinations ?? [];
    $destinationsAr = $destinationsAr ?? [];
    $destinationItems = $destinations['items'] ?? [];
    $destinationItemsAr = $destinationsAr['items'] ?? [];
    $services = $services ?? [];
    $servicesAr = $servicesAr ?? [];
    $serviceItems = $services['items'] ?? [];
    $serviceItemsAr = $servicesAr['items'] ?? [];
    $inquiry = $inquiry ?? [];
    $inquiryAr = $inquiryAr ?? [];

    $featureCount = max(count($features), count($featuresAr), 4);
    $destinationCount = max(count($destinationItems), count($destinationItemsAr), 4);
    $serviceCount = max(count($serviceItems), count($serviceItemsAr), 6);
    $bulletCount = max(count($inquiry['bullets'] ?? []), count($inquiryAr['bullets'] ?? []), 3);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Travel & Tourism</h2>
            <p class="text-sm text-gray-500 mt-1">All sections are static and editable.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Hero</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.travel.hero') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Badge (EN)</label>
                    <input name="badge" value="{{ old('badge', $hero['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Badge (AR)</label>
                    <input name="badge_ar" value="{{ old('badge_ar', $heroAr['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                    <input name="title" value="{{ old('title', $hero['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                    <input name="title_ar" value="{{ old('title_ar', $heroAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                    <textarea name="description" rows="3" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $hero['description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                    <textarea name="description_ar" rows="3" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('description_ar', $heroAr['description'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Background image URL</label>
                    <input name="background_image" value="{{ old('background_image', $hero['background_image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input type="file" name="background_image_file" class="mt-2 w-full text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Primary CTA text (EN)</label>
                    <input name="primary_cta_text" value="{{ old('primary_cta_text', $hero['primary_cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="primary_cta_text_ar" value="{{ old('primary_cta_text_ar', $heroAr['primary_cta_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="primary_cta_url" value="{{ old('primary_cta_url', $hero['primary_cta_url'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="URL">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Secondary CTA text (EN)</label>
                    <input name="secondary_cta_text" value="{{ old('secondary_cta_text', $hero['secondary_cta_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="secondary_cta_text_ar" value="{{ old('secondary_cta_text_ar', $heroAr['secondary_cta_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="secondary_cta_url" value="{{ old('secondary_cta_url', $hero['secondary_cta_url'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="URL">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Hero</button>
            </div>
        </form>
    </div>

    {{-- CTA CARD --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">CTA Card</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.travel.cta') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $cta['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $ctaAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (EN)</label>
                    <input name="subheading" value="{{ old('subheading', $cta['subheading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (AR)</label>
                    <input name="subheading_ar" value="{{ old('subheading_ar', $ctaAr['subheading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">WhatsApp text (EN)</label>
                    <input name="whatsapp_text" value="{{ old('whatsapp_text', $cta['whatsapp_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="whatsapp_text_ar" value="{{ old('whatsapp_text_ar', $ctaAr['whatsapp_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="whatsapp_url" value="{{ old('whatsapp_url', $cta['whatsapp_url'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="URL">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Call text (EN)</label>
                    <input name="call_text" value="{{ old('call_text', $cta['call_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="call_text_ar" value="{{ old('call_text_ar', $ctaAr['call_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="call_url" value="{{ old('call_url', $cta['call_url'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="URL">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Inquire text (EN)</label>
                    <input name="inquire_text" value="{{ old('inquire_text', $cta['inquire_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="inquire_text_ar" value="{{ old('inquire_text_ar', $ctaAr['inquire_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="inquire_url" value="{{ old('inquire_url', $cta['inquire_url'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="URL">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save CTA</button>
            </div>
        </form>
    </div>

    {{-- FEATURES --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Features</h3>
            <span class="text-xs text-gray-500">Icon name, title, description</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.travel.features') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="space-y-3">
                @for($i = 0; $i < $featureCount; $i++)
                    @php
                        $f = $features[$i] ?? [];
                        $fAr = $featuresAr[$i] ?? [];
                    @endphp
                    <div class="border border-gray-100 rounded-lg p-3 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icon</label>
                            <input name="items[{{ $i }}][icon]" value="{{ old("items.$i.icon", $f['icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="faHeadset">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                            <input name="items[{{ $i }}][title]" value="{{ old("items.$i.title", $f['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                            <input name="items[{{ $i }}][title_ar]" value="{{ old("items.$i.title_ar", $fAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                            <textarea name="items[{{ $i }}][description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old("items.$i.description", $f['description'] ?? '') }}</textarea>
                            <textarea name="items[{{ $i }}][description_ar]" rows="2" class="mt-2 w-full border rounded px-3 py-2" dir="rtl" placeholder="AR">{{ old("items.$i.description_ar", $fAr['description'] ?? '') }}</textarea>
                        </div>
                    </div>
                @endfor
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Features</button>
            </div>
        </form>
    </div>

    {{-- DESTINATIONS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Destinations</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.travel.destinations') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Eyebrow (EN)</label>
                    <input name="eyebrow" value="{{ old('eyebrow', $destinations['eyebrow'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Eyebrow (AR)</label>
                    <input name="eyebrow_ar" value="{{ old('eyebrow_ar', $destinationsAr['eyebrow'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $destinations['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $destinationsAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">View all text (EN)</label>
                    <input name="view_all_text" value="{{ old('view_all_text', $destinations['view_all_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="view_all_text_ar" value="{{ old('view_all_text_ar', $destinationsAr['view_all_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="view_all_url" value="{{ old('view_all_url', $destinations['view_all_url'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="URL">
                </div>
            </div>
            <div class="space-y-3">
                @for($i = 0; $i < $destinationCount; $i++)
                    @php
                        $d = $destinationItems[$i] ?? [];
                        $dAr = $destinationItemsAr[$i] ?? [];
                    @endphp
                    <div class="border border-gray-100 rounded-lg p-3 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name (EN)</label>
                            <input name="items[{{ $i }}][name]" value="{{ old("items.$i.name", $d['name'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            <input name="items[{{ $i }}][name_ar]" value="{{ old("items.$i.name_ar", $dAr['name'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price (EN)</label>
                            <input name="items[{{ $i }}][price]" value="{{ old("items.$i.price", $d['price'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            <input name="items[{{ $i }}][price_ar]" value="{{ old("items.$i.price_ar", $dAr['price'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tag (EN)</label>
                            <input name="items[{{ $i }}][tag]" value="{{ old("items.$i.tag", $d['tag'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            <input name="items[{{ $i }}][tag_ar]" value="{{ old("items.$i.tag_ar", $dAr['tag'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image URL</label>
                            <input name="items[{{ $i }}][image]" value="{{ old("items.$i.image", $d['image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            <input type="file" name="items[{{ $i }}][image_file]" class="mt-2 w-full text-sm">
                        </div>
                    </div>
                @endfor
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Destinations</button>
            </div>
        </form>
    </div>

    {{-- SERVICES --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Services</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.travel.services') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Eyebrow (EN)</label>
                    <input name="eyebrow" value="{{ old('eyebrow', $services['eyebrow'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Eyebrow (AR)</label>
                    <input name="eyebrow_ar" value="{{ old('eyebrow_ar', $servicesAr['eyebrow'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $services['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $servicesAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                    <textarea name="description" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $services['description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                    <textarea name="description_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('description_ar', $servicesAr['description'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="space-y-3">
                @for($i = 0; $i < $serviceCount; $i++)
                    @php
                        $s = $serviceItems[$i] ?? [];
                        $sAr = $serviceItemsAr[$i] ?? [];
                    @endphp
                    <div class="border border-gray-100 rounded-lg p-3 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Icon</label>
                            <input name="items[{{ $i }}][icon]" value="{{ old("items.$i.icon", $s['icon'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="faPlaneDeparture">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                            <input name="items[{{ $i }}][title]" value="{{ old("items.$i.title", $s['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                            <input name="items[{{ $i }}][title_ar]" value="{{ old("items.$i.title_ar", $sAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                            <textarea name="items[{{ $i }}][description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old("items.$i.description", $s['description'] ?? '') }}</textarea>
                            <textarea name="items[{{ $i }}][description_ar]" rows="2" class="mt-2 w-full border rounded px-3 py-2" dir="rtl" placeholder="AR">{{ old("items.$i.description_ar", $sAr['description'] ?? '') }}</textarea>
                        </div>
                    </div>
                @endfor
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Services</button>
            </div>
        </form>
    </div>

    {{-- INQUIRY FORM --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Inquiry Section</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.travel.inquiry') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                    <input name="title" value="{{ old('title', $inquiry['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                    <input name="title_ar" value="{{ old('title_ar', $inquiryAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                    <textarea name="description" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $inquiry['description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                    <textarea name="description_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('description_ar', $inquiryAr['description'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bullets (EN)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $bulletCount; $i++)
                            <input name="bullets[{{ $i }}]" value="{{ old("bullets.$i", $inquiry['bullets'][$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Bullet {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bullets (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $bulletCount; $i++)
                            <input name="bullets_ar[{{ $i }}]" value="{{ old("bullets_ar.$i", $inquiryAr['bullets'][$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Bullet {{ $i+1 }}" dir="rtl">
                        @endfor
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">WhatsApp text (EN)</label>
                    <input name="whatsapp_text" value="{{ old('whatsapp_text', $inquiry['whatsapp_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="whatsapp_text_ar" value="{{ old('whatsapp_text_ar', $inquiryAr['whatsapp_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="whatsapp_url" value="{{ old('whatsapp_url', $inquiry['whatsapp_url'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="URL">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Form title (EN)</label>
                    <input name="form_title" value="{{ old('form_title', $inquiry['form_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="form_title_ar" value="{{ old('form_title_ar', $inquiryAr['form_title'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name label (EN)</label>
                    <input name="form_name_label" value="{{ old('form_name_label', $inquiry['form_name_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="form_name_label_ar" value="{{ old('form_name_label_ar', $inquiryAr['form_name_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="form_name_placeholder" value="{{ old('form_name_placeholder', $inquiry['form_name_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="form_name_placeholder_ar" value="{{ old('form_name_placeholder_ar', $inquiryAr['form_name_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone label (EN)</label>
                    <input name="form_phone_label" value="{{ old('form_phone_label', $inquiry['form_phone_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="form_phone_label_ar" value="{{ old('form_phone_label_ar', $inquiryAr['form_phone_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="form_phone_placeholder" value="{{ old('form_phone_placeholder', $inquiry['form_phone_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="form_phone_placeholder_ar" value="{{ old('form_phone_placeholder_ar', $inquiryAr['form_phone_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Destination label (EN)</label>
                    <input name="form_destination_label" value="{{ old('form_destination_label', $inquiry['form_destination_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="form_destination_label_ar" value="{{ old('form_destination_label_ar', $inquiryAr['form_destination_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="form_destination_placeholder" value="{{ old('form_destination_placeholder', $inquiry['form_destination_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="form_destination_placeholder_ar" value="{{ old('form_destination_placeholder_ar', $inquiryAr['form_destination_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date label (EN)</label>
                    <input name="form_date_label" value="{{ old('form_date_label', $inquiry['form_date_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="form_date_label_ar" value="{{ old('form_date_label_ar', $inquiryAr['form_date_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Message label (EN)</label>
                    <input name="form_message_label" value="{{ old('form_message_label', $inquiry['form_message_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="form_message_label_ar" value="{{ old('form_message_label_ar', $inquiryAr['form_message_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="form_message_placeholder" value="{{ old('form_message_placeholder', $inquiry['form_message_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="form_message_placeholder_ar" value="{{ old('form_message_placeholder_ar', $inquiryAr['form_message_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Submit text (EN)</label>
                    <input name="form_submit_text" value="{{ old('form_submit_text', $inquiry['form_submit_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Submit text (AR)</label>
                    <input name="form_submit_text_ar" value="{{ old('form_submit_text_ar', $inquiryAr['form_submit_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Inquiry</button>
            </div>
        </form>
    </div>
</div>
@endsection
