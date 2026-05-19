@extends('admin.layouts.layout')

@php
    $hero = $hero ?? [];
    $heroAr = $heroAr ?? [];
    $results = $results ?? [];
    $resultsAr = $resultsAr ?? [];
    $loadMore = $loadMore ?? [];
    $loadMoreAr = $loadMoreAr ?? [];

    $levelOptions = array_values($hero['level_options'] ?? []);
    $levelOptionsAr = array_values($heroAr['level_options'] ?? []);
    $levelCount = max(count($levelOptions), count($levelOptionsAr), 3);

    $focusOptions = array_values($hero['focus_options'] ?? []);
    $focusOptionsAr = array_values($heroAr['focus_options'] ?? []);
    $focusCount = max(count($focusOptions), count($focusOptionsAr), 4);

    $scheduleOptions = array_values($hero['schedule_options'] ?? []);
    $scheduleOptionsAr = array_values($heroAr['schedule_options'] ?? []);
    $scheduleCount = max(count($scheduleOptions), count($scheduleOptionsAr), 4);

    $sortOptions = array_values($results['sort_options'] ?? []);
    $sortOptionsAr = array_values($resultsAr['sort_options'] ?? []);
    $sortCount = max(count($sortOptions), count($sortOptionsAr), 4);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Online Courses</h2>
            <p class="text-sm text-gray-500 mt-1">Static labels only; course cards are dynamic.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Hero</h3>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.online-courses.hero') }}" class="space-y-4">
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
                    <label class="block text-sm font-medium text-gray-700">Level label (EN)</label>
                    <input name="level_label" value="{{ old('level_label', $hero['level_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="level_label_ar" value="{{ old('level_label_ar', $heroAr['level_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="level_placeholder" value="{{ old('level_placeholder', $hero['level_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="level_placeholder_ar" value="{{ old('level_placeholder_ar', $heroAr['level_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Focus label (EN)</label>
                    <input name="focus_label" value="{{ old('focus_label', $hero['focus_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="focus_label_ar" value="{{ old('focus_label_ar', $heroAr['focus_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="focus_placeholder" value="{{ old('focus_placeholder', $hero['focus_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="focus_placeholder_ar" value="{{ old('focus_placeholder_ar', $heroAr['focus_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Schedule label (EN)</label>
                    <input name="schedule_label" value="{{ old('schedule_label', $hero['schedule_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="schedule_label_ar" value="{{ old('schedule_label_ar', $heroAr['schedule_label'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR" dir="rtl">
                    <input name="schedule_placeholder" value="{{ old('schedule_placeholder', $hero['schedule_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                    <input name="schedule_placeholder_ar" value="{{ old('schedule_placeholder_ar', $heroAr['schedule_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="AR placeholder" dir="rtl">
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
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Level options (EN)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $levelCount; $i++)
                            <input name="level_options[{{ $i }}]" value="{{ old("level_options.$i", $levelOptions[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Level options (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $levelCount; $i++)
                            <input name="level_options_ar[{{ $i }}]" value="{{ old("level_options_ar.$i", $levelOptionsAr[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}" dir="rtl">
                        @endfor
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Focus options (EN)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $focusCount; $i++)
                            <input name="focus_options[{{ $i }}]" value="{{ old("focus_options.$i", $focusOptions[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Focus options (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $focusCount; $i++)
                            <input name="focus_options_ar[{{ $i }}]" value="{{ old("focus_options_ar.$i", $focusOptionsAr[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}" dir="rtl">
                        @endfor
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Schedule options (EN)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $scheduleCount; $i++)
                            <input name="schedule_options[{{ $i }}]" value="{{ old("schedule_options.$i", $scheduleOptions[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Schedule options (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $scheduleCount; $i++)
                            <input name="schedule_options_ar[{{ $i }}]" value="{{ old("schedule_options_ar.$i", $scheduleOptionsAr[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}" dir="rtl">
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
        <form method="POST" action="{{ route('admin.cms.course-english.online-courses.results') }}" class="space-y-4">
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
        <form method="POST" action="{{ route('admin.cms.course-english.online-courses.load-more') }}" class="space-y-4">
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
