@extends('admin.layouts.layout')

@php
    $hero = $hero ?? [];
    $heroAr = $heroAr ?? [];
    $cards = $cards ?? [];
    $cardsAr = $cardsAr ?? [];
    $stats = $stats ?? [];
    $statsAr = $statsAr ?? [];

    $cardCount = max(count($cards), count($cardsAr), 2);
    $statCount = max(count($stats), count($statsAr), 4);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">University Admissions</h2>
            <p class="text-sm text-gray-500 mt-1">All content is static and editable.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Hero</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.university-admissions.hero') }}" class="space-y-4">
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
                    <textarea name="description" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $hero['description'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
                    <textarea name="description_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('description_ar', $heroAr['description'] ?? '') }}</textarea>
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Hero</button>
            </div>
        </form>
    </div>

    {{-- CARDS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Action Cards</h3>
            <span class="text-xs text-gray-500">Image, icon, title, description, CTA</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.university-admissions.cards') }}" class="space-y-4" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="space-y-3">
                @for($i = 0; $i < $cardCount; $i++)
                    @php
                        $c = $cards[$i] ?? [];
                        $cAr = $cardsAr[$i] ?? [];
                    @endphp
                    <div class="border border-gray-100 rounded-lg p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                            <input name="cards[{{ $i }}][title]" value="{{ old("cards.$i.title", $c['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            <input name="cards[{{ $i }}][title_ar]" value="{{ old("cards.$i.title_ar", $cAr['title'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description (EN)</label>
                            <textarea name="cards[{{ $i }}][description]" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old("cards.$i.description", $c['description'] ?? '') }}</textarea>
                            <textarea name="cards[{{ $i }}][description_ar]" rows="2" class="mt-2 w-full border rounded px-3 py-2" dir="rtl" placeholder="AR">{{ old("cards.$i.description_ar", $cAr['description'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Button text (EN)</label>
                            <input name="cards[{{ $i }}][button_text]" value="{{ old("cards.$i.button_text", $c['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            <input name="cards[{{ $i }}][button_text_ar]" value="{{ old("cards.$i.button_text_ar", $cAr['button_text'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                            <input name="cards[{{ $i }}][url]" value="{{ old("cards.$i.url", $c['url'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="URL">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image URL</label>
                            <input name="cards[{{ $i }}][image]" value="{{ old("cards.$i.image", $c['image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            <input type="file" name="cards[{{ $i }}][image_file]" class="mt-2 w-full text-sm">
                            <input name="cards[{{ $i }}][icon]" value="{{ old("cards.$i.icon", $c['icon'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Icon name (e.g. faUniversity)">
                        </div>
                    </div>
                @endfor
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Cards</button>
            </div>
        </form>
    </div>

    {{-- STATS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Stats</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.university-admissions.stats') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="space-y-3">
                @for($i = 0; $i < $statCount; $i++)
                    <div class="border border-gray-100 rounded-lg p-3 grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Value</label>
                            <input name="stats[{{ $i }}][value]" value="{{ old("stats.$i.value", $stats[$i]['value'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Label (EN)</label>
                            <input name="stats[{{ $i }}][label]" value="{{ old("stats.$i.label", $stats[$i]['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Label (AR)</label>
                            <input name="stats[{{ $i }}][label_ar]" value="{{ old("stats.$i.label_ar", $statsAr[$i]['label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        </div>
                    </div>
                @endfor
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Stats</button>
            </div>
        </form>
    </div>
</div>
@endsection
