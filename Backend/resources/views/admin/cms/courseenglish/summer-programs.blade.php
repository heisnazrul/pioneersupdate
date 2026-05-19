@extends('admin.layouts.layout')

@php
    $hero = $hero ?? [];
    $heroAr = $heroAr ?? [];
    $results = $results ?? [];
    $resultsAr = $resultsAr ?? [];
    $loadMore = $loadMore ?? [];
    $loadMoreAr = $loadMoreAr ?? [];

    $ageOptions = array_values($hero['age_options'] ?? []);
    $ageOptionsAr = array_values($heroAr['age_options'] ?? []);
    $ageCount = max(count($ageOptions), count($ageOptionsAr), 3);

    $durationOptions = array_values($hero['duration_options'] ?? []);
    $durationOptionsAr = array_values($heroAr['duration_options'] ?? []);
    $durationCount = max(count($durationOptions), count($durationOptionsAr), 4);

    $sortOptions = array_values($results['sort_options'] ?? []);
    $sortOptionsAr = array_values($resultsAr['sort_options'] ?? []);
    $sortCount = max(count($sortOptions), count($sortOptionsAr), 4);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Summer Programs</h2>
            <p class="text-sm text-gray-500 mt-1">Static labels only; camp cards are dynamic.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Hero</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.summer-programs.hero') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (EN)</label>
                    <input name="heading" value="{{ old('heading', $hero['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Heading (AR)</label>
                    <input name="heading_ar" value="{{ old('heading_ar', $heroAr['heading'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (EN)</label>
                    <textarea name="subheading" rows="2" class="mt-1 w-full border rounded px-3 py-2">{{ old('subheading', $hero['subheading'] ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Subheading (AR)</label>
                    <textarea name="subheading_ar" rows="2" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('subheading_ar', $heroAr['subheading'] ?? '') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Destination label (EN)</label>
                    <input name="destination_label" value="{{ old('destination_label', $hero['destination_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="destination_label_ar" value="{{ old('destination_label_ar', $heroAr['destination_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="destination_placeholder" value="{{ old('destination_placeholder', $hero['destination_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="destination_placeholder_ar" value="{{ old('destination_placeholder_ar', $heroAr['destination_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Age label (EN)</label>
                    <input name="age_label" value="{{ old('age_label', $hero['age_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="age_label_ar" value="{{ old('age_label_ar', $heroAr['age_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="age_placeholder" value="{{ old('age_placeholder', $hero['age_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="age_placeholder_ar" value="{{ old('age_placeholder_ar', $heroAr['age_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Duration label (EN)</label>
                    <input name="duration_label" value="{{ old('duration_label', $hero['duration_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="duration_label_ar" value="{{ old('duration_label_ar', $heroAr['duration_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="duration_placeholder" value="{{ old('duration_placeholder', $hero['duration_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="duration_placeholder_ar" value="{{ old('duration_placeholder_ar', $heroAr['duration_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start label (EN)</label>
                    <input name="start_label" value="{{ old('start_label', $hero['start_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="start_label_ar" value="{{ old('start_label_ar', $heroAr['start_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="start_placeholder" value="{{ old('start_placeholder', $hero['start_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="start_placeholder_ar" value="{{ old('start_placeholder_ar', $heroAr['start_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Age options (EN)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $ageCount; $i++)
                            <input name="age_options[{{ $i }}]" value="{{ old("age_options.$i", $ageOptions[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Age options (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $ageCount; $i++)
                            <input name="age_options_ar[{{ $i }}]" value="{{ old("age_options_ar.$i", $ageOptionsAr[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}" dir="rtl">
                        @endfor
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Duration options (EN)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $durationCount; $i++)
                            <input name="duration_options[{{ $i }}]" value="{{ old("duration_options.$i", $durationOptions[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Duration options (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $durationCount; $i++)
                            <input name="duration_options_ar[{{ $i }}]" value="{{ old("duration_options_ar.$i", $durationOptionsAr[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}" dir="rtl">
                        @endfor
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search aria label (EN)</label>
                    <input name="search_aria" value="{{ old('search_aria', $hero['search_aria'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Search aria label (AR)</label>
                    <input name="search_aria_ar" value="{{ old('search_aria_ar', $heroAr['search_aria'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Hero</button>
            </div>
        </form>
    </div>

    {{-- RESULTS HEADER --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Results Header</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.summer-programs.results') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Count label (EN)</label>
                    <input name="count_label" value="{{ old('count_label', $results['count_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Count label (AR)</label>
                    <input name="count_label_ar" value="{{ old('count_label_ar', $resultsAr['count_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Step text (EN)</label>
                    <input name="step_label" value="{{ old('step_label', $results['step_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Step text (AR)</label>
                    <input name="step_label_ar" value="{{ old('step_label_ar', $resultsAr['step_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sort label (EN)</label>
                    <input name="sort_label" value="{{ old('sort_label', $results['sort_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sort label (AR)</label>
                    <input name="sort_label_ar" value="{{ old('sort_label_ar', $resultsAr['sort_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort options (EN)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $sortCount; $i++)
                            <input name="sort_options[{{ $i }}]" value="{{ old("sort_options.$i", $sortOptions[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort options (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $sortCount; $i++)
                            <input name="sort_options_ar[{{ $i }}]" value="{{ old("sort_options_ar.$i", $sortOptionsAr[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}" dir="rtl">
                        @endfor
                    </div>
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Results Header</button>
            </div>
        </form>
    </div>

    {{-- LOAD MORE --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Load More Button</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.summer-programs.load-more') }}" class="space-y-4">
            @csrf
            @method('PATCH')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Text (EN)</label>
                    <input name="text" value="{{ old('text', $loadMore['text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Text (AR)</label>
                    <input name="text_ar" value="{{ old('text_ar', $loadMoreAr['text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                </div>
            </div>
            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Load More</button>
            </div>
        </form>
    </div>
</div>
@endsection
