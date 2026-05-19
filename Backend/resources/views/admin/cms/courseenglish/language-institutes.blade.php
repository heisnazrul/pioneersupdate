@extends('admin.layouts.layout')

@php
    $hero = $hero ?? [];
    $heroAr = $heroAr ?? [];
    $results = $results ?? [];
    $resultsAr = $resultsAr ?? [];
    $sidebar = $sidebar ?? [];
    $sidebarAr = $sidebarAr ?? [];
    $loadMore = $loadMore ?? [];
    $loadMoreAr = $loadMoreAr ?? [];

    $courseOptions = array_values($hero['course_options'] ?? []);
    $courseOptionsAr = array_values($heroAr['course_options'] ?? []);
    $courseOptionCount = max(count($courseOptions), count($courseOptionsAr), 3);

    $sortOptions = array_values($results['sort_options'] ?? []);
    $sortOptionsAr = array_values($resultsAr['sort_options'] ?? []);
    $sortOptionCount = max(count($sortOptions), count($sortOptionsAr), 4);

    $sidebarSections = $sidebar['sections'] ?? [];
    $sidebarSectionsAr = $sidebarAr['sections'] ?? [];
    $sectionCount = max(count($sidebarSections), count($sidebarSectionsAr), 3);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / CourseEnglish</p>
            <h2 class="text-2xl font-bold">Language Institutes Page</h2>
            <p class="text-sm text-gray-500 mt-1">Edit static text, labels, and filters. Cards remain dynamic.</p>
        </div>
    </div>

    {{-- HERO --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Hero</h3>
            <span class="text-xs text-gray-500">Headings, labels, placeholders</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institutes.hero') }}" class="space-y-4">
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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Destination label</label>
                    <input name="destination_label" value="{{ old('destination_label', $hero['destination_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="destination_placeholder" value="{{ old('destination_placeholder', $hero['destination_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Course label</label>
                    <input name="course_label" value="{{ old('course_label', $hero['course_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="course_placeholder" value="{{ old('course_placeholder', $hero['course_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Duration label</label>
                    <input name="duration_label" value="{{ old('duration_label', $hero['duration_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="duration_placeholder" value="{{ old('duration_placeholder', $hero['duration_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Start date label</label>
                    <input name="start_label" value="{{ old('start_label', $hero['start_label'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    <input name="start_placeholder" value="{{ old('start_placeholder', $hero['start_placeholder'] ?? '') }}" class="mt-2 w-full border rounded px-3 py-2" placeholder="Placeholder">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Course options (EN)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $courseOptionCount; $i++)
                            <input name="course_options[{{ $i }}]" value="{{ old("course_options.$i", $courseOptions[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Course options (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $courseOptionCount; $i++)
                            <input name="course_options_ar[{{ $i }}]" value="{{ old("course_options_ar.$i", $courseOptionsAr[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}" dir="rtl">
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
            <span class="text-xs text-gray-500">Count label, step text, sort options</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institutes.results') }}" class="space-y-4">
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
                        @for($i = 0; $i < $sortOptionCount; $i++)
                            <input name="sort_options[{{ $i }}]" value="{{ old("sort_options.$i", $sortOptions[$i] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $i+1 }}">
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort options (AR)</label>
                    <div class="space-y-2">
                        @for($i = 0; $i < $sortOptionCount; $i++)
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

    {{-- SIDEBAR FILTERS --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Sidebar Filters</h3>
            <span class="text-xs text-gray-500">Section titles and option labels</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institutes.sidebar') }}" class="space-y-4">
            @csrf
            @method('PATCH')

            <div class="space-y-3">
                @for($i = 0; $i < $sectionCount; $i++)
                    @php
                        $section = $sidebarSections[$i] ?? [];
                        $sectionAr = $sidebarSectionsAr[$i] ?? [];
                        $options = array_values($section['options'] ?? []);
                        $optionsAr = array_values($sectionAr['options'] ?? []);
                        $optionCount = max(count($options), count($optionsAr), 2);
                    @endphp
                    <div class="border border-gray-100 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Section title (EN)</label>
                                <input name="sections[{{ $i }}][title]" value="{{ old("sections.$i.title", $section['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Section title (AR)</label>
                                <input name="sections[{{ $i }}][title_ar]" value="{{ old("sections.$i.title_ar", $sectionAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Options (EN)</label>
                                <div class="space-y-2">
                                    @for($j = 0; $j < $optionCount; $j++)
                                        <input name="sections[{{ $i }}][options][{{ $j }}]" value="{{ old("sections.$i.options.$j", $options[$j] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $j+1 }}">
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Options (AR)</label>
                                <div class="space-y-2">
                                    @for($j = 0; $j < $optionCount; $j++)
                                        <input name="sections[{{ $i }}][options_ar][{{ $j }}]" value="{{ old("sections.$i.options_ar.$j", $optionsAr[$j] ?? '') }}" class="w-full border rounded px-3 py-2" placeholder="Option {{ $j+1 }}" dir="rtl">
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Sidebar</button>
            </div>
        </form>
    </div>

    {{-- LOAD MORE --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">Load More Button</h3>
            <span class="text-xs text-gray-500">Button label</span>
        </div>
        <form method="POST" action="{{ route('admin.cms.course-english.language-institutes.load-more') }}" class="space-y-4">
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
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save Button</button>
            </div>
        </form>
    </div>
</div>
@endsection
