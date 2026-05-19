@extends('admin.layouts.layout')

@php
    $contactItems = $contactInfo['items'] ?? [];
    $contactItemsAr = $contactInfoAr['items'] ?? [];
    $contactCount = max(count($contactItems), 4);
    $officeItems = $offices['items'] ?? [];
    $officeItemsAr = $officesAr['items'] ?? [];
    $officeCount = max(count($officeItems), 4);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / University</p>
            <h2 class="text-2xl font-bold">Contact Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static contact content only.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="POST" action="{{ route('admin.cms.university.contact.update') }}" class="space-y-6">
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
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Contact Info</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="contact_info[title]" value="{{ old('contact_info.title', $contactInfo['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="contact_info[title_ar]" value="{{ old('contact_info.title_ar', $contactInfoAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="contact_info[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('contact_info.description', $contactInfo['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="contact_info[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('contact_info.description_ar', $contactInfoAr['description'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $contactCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Title (EN)</label>
                            <input name="contact_info[items][{{ $i }}][title]" value="{{ old("contact_info.items.$i.title", $contactItems[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Title (AR)</label>
                            <input name="contact_info[items][{{ $i }}][title_ar]" value="{{ old("contact_info.items.$i.title_ar", $contactItemsAr[$i]['title'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Value (EN)</label>
                            <input name="contact_info[items][{{ $i }}][value]" value="{{ old("contact_info.items.$i.value", $contactItems[$i]['value'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Value (AR)</label>
                            <input name="contact_info[items][{{ $i }}][value_ar]" value="{{ old("contact_info.items.$i.value_ar", $contactItemsAr[$i]['value'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Icon</label>
                            <input name="contact_info[items][{{ $i }}][icon]" value="{{ old("contact_info.items.$i.icon", $contactItems[$i]['icon'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Offices</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="offices[title]" value="{{ old('offices.title', $offices['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="offices[title_ar]" value="{{ old('offices.title_ar', $officesAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                        <textarea name="offices[description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('offices.description', $offices['description'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                        <textarea name="offices[description_ar]" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('offices.description_ar', $officesAr['description'] ?? '') }}</textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @for($i = 0; $i < $officeCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">City (EN)</label>
                            <input name="offices[items][{{ $i }}][city]" value="{{ old("offices.items.$i.city", $officeItems[$i]['city'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">City (AR)</label>
                            <input name="offices[items][{{ $i }}][city_ar]" value="{{ old("offices.items.$i.city_ar", $officeItemsAr[$i]['city'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Address (EN)</label>
                            <input name="offices[items][{{ $i }}][address]" value="{{ old("offices.items.$i.address", $officeItems[$i]['address'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Address (AR)</label>
                            <input name="offices[items][{{ $i }}][address_ar]" value="{{ old("offices.items.$i.address_ar", $officeItemsAr[$i]['address'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Phone</label>
                            <input name="offices[items][{{ $i }}][phone]" value="{{ old("offices.items.$i.phone", $officeItems[$i]['phone'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Email</label>
                            <input name="offices[items][{{ $i }}][email]" value="{{ old("offices.items.$i.email", $officeItems[$i]['email'] ?? '') }}" class="w-full border rounded px-2 py-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Contact Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
