@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / University</p>
            <h2 class="text-2xl font-bold">Destinations Page</h2>
            <p class="text-sm text-gray-500 mt-1">Edit hero text, filter labels, UI strings, and CTA section.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="POST" action="{{ route('admin.cms.university.destinations.update') }}" class="space-y-6">
            @csrf
            @method('PATCH')

            {{-- ── Hero Section ── --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Hero</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="hero_title" value="{{ old('hero_title', $content['hero_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="hero_title_ar" value="{{ old('hero_title_ar', $arContent['hero_title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (EN)</label>
                        <textarea name="hero_subtitle" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('hero_subtitle', $content['hero_subtitle'] ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Subtitle (AR)</label>
                        <textarea name="hero_subtitle_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('hero_subtitle_ar', $arContent['hero_subtitle'] ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── Filter Labels ── --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Filter Labels</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ([
                        'filter_all'     => 'All (Filter)',
                        'filter_europe'  => 'Europe (Filter)',
                        'filter_na'      => 'North America (Filter)',
                        'filter_oceania' => 'Oceania (Filter)',
                        'filter_budget'  => 'Low Tuition (Filter)',
                    ] as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $label }} (EN)</label>
                            <input name="{{ $key }}" value="{{ old($key, $content[$key] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $label }} (AR)</label>
                            <input name="{{ $key }}_ar" value="{{ old($key.'_ar', $arContent[$key] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── UI Text Labels ── --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">UI Text Labels</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ([
                        'search_placeholder' => 'Search Placeholder',
                        'no_match_title'     => 'No Results Title',
                        'no_match_text'      => 'No Results Description',
                        'reset_filters'      => 'Reset Filters Button',
                        'showing'            => 'Showing (count prefix)',
                        'destinations_word'  => 'Destinations (count suffix)',
                    ] as $key => $label)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $label }} (EN)</label>
                            <input name="{{ $key }}" value="{{ old($key, $content[$key] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ $label }} (AR)</label>
                            <input name="{{ $key }}_ar" value="{{ old($key.'_ar', $arContent[$key] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- ── CTA Section ── --}}
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">CTA Section</h3>
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
                        <label class="block text-sm font-medium text-gray-700">Button Text (EN)</label>
                        <input name="cta[button_text]" value="{{ old('cta.button_text', $cta['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Button Text (AR)</label>
                        <input name="cta[button_text_ar]" value="{{ old('cta.button_text_ar', $ctaAr['button_text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Button Link</label>
                        <input name="cta[button_link]" value="{{ old('cta.button_link', $cta['button_link'] ?? '/contact') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="/contact">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Destinations Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
