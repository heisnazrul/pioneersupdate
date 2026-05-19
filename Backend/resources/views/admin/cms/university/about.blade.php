@extends('admin.layouts.layout')

@php
    $team = $team ?? [];
    $teamAr = $teamAr ?? [];
    $teamCount = max(count($team), 6);
@endphp

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / University</p>
            <h2 class="text-2xl font-bold">About Page</h2>
            <p class="text-sm text-gray-500 mt-1">Static content only.</p>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="POST" action="{{ route('admin.cms.university.about.update') }}" class="space-y-6" enctype="multipart/form-data">
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
                <h3 class="text-xl font-semibold mb-4">Director Message</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="director[title]" value="{{ old('director.title', $director['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="director_ar[title]" value="{{ old('director_ar.title', $directorAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Name (EN)</label>
                        <input name="director[name]" value="{{ old('director.name', $director['name'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Name (AR)</label>
                        <input name="director_ar[name]" value="{{ old('director_ar.name', $directorAr['name'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Role (EN)</label>
                        <input name="director[role]" value="{{ old('director.role', $director['role'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Role (AR)</label>
                        <input name="director_ar[role]" value="{{ old('director_ar.role', $directorAr['role'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Message (EN) — split into paragraphs by line</label>
                        <textarea name="director[message]" rows="5" class="mt-1 w-full border rounded px-3 py-2">{{ old('director.message', isset($director['paragraphs']) ? implode("\n", $director['paragraphs']) : '') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Message (AR) — split into paragraphs by line</label>
                        <textarea name="director_ar[message]" rows="5" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('director_ar.message', isset($directorAr['paragraphs']) ? implode("\n", $directorAr['paragraphs']) : '') }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Closing text (EN)</label>
                        <input name="director[closing_text]" value="{{ old('director.closing_text', $director['closing']['text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Closing text (AR)</label>
                        <input name="director_ar[closing_text]" value="{{ old('director_ar.closing_text', $directorAr['closing']['text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Closing name</label>
                        <input name="director[closing_name]" value="{{ old('director.closing_name', $director['closing']['name'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Closing position</label>
                        <input name="director[closing_position]" value="{{ old('director.closing_position', $director['closing']['position'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Image URL</label>
                        <input name="director[image]" value="{{ old('director.image', $director['image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">CEO Message</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="ceo[title]" value="{{ old('ceo.title', $ceo['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="ceo_ar[title]" value="{{ old('ceo_ar.title', $ceoAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Name (EN)</label>
                        <input name="ceo[name]" value="{{ old('ceo.name', $ceo['name'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Name (AR)</label>
                        <input name="ceo_ar[name]" value="{{ old('ceo_ar.name', $ceoAr['name'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Role (EN)</label>
                        <input name="ceo[role]" value="{{ old('ceo.role', $ceo['role'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Role (AR)</label>
                        <input name="ceo_ar[role]" value="{{ old('ceo_ar.role', $ceoAr['role'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Message (EN) — split into paragraphs by line</label>
                        <textarea name="ceo[message]" rows="5" class="mt-1 w-full border rounded px-3 py-2">{{ old('ceo.message', isset($ceo['paragraphs']) ? implode("\n", $ceo['paragraphs']) : '') }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Message (AR) — split into paragraphs by line</label>
                        <textarea name="ceo_ar[message]" rows="5" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">{{ old('ceo_ar.message', isset($ceoAr['paragraphs']) ? implode("\n", $ceoAr['paragraphs']) : '') }}</textarea>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Closing text (EN)</label>
                        <input name="ceo[closing_text]" value="{{ old('ceo.closing_text', $ceo['closing']['text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Closing text (AR)</label>
                        <input name="ceo_ar[closing_text]" value="{{ old('ceo_ar.closing_text', $ceoAr['closing']['text'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Closing name</label>
                        <input name="ceo[closing_name]" value="{{ old('ceo.closing_name', $ceo['closing']['name'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Closing position</label>
                        <input name="ceo[closing_position]" value="{{ old('ceo.closing_position', $ceo['closing']['position'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-700">Image URL</label>
                        <input name="ceo[image]" value="{{ old('ceo.image', $ceo['image'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xl font-semibold mb-4">Team</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700">Badge (EN)</label>
                        <input name="team[badge]" value="{{ old('team.badge', $teamMeta['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Badge (AR)</label>
                        <input name="team[badge_ar]" value="{{ old('team.badge_ar', $teamMetaAr['badge'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Title (EN)</label>
                        <input name="team[title]" value="{{ old('team.title', $teamMeta['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700">Title (AR)</label>
                        <input name="team[title_ar]" value="{{ old('team.title_ar', $teamMetaAr['title'] ?? '') }}" class="mt-1 w-full border rounded px-3 py-2" dir="rtl">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @for($i = 0; $i < $teamCount; $i++)
                        <div class="border rounded-lg p-3">
                            <label class="text-xs font-semibold text-gray-600">Name (EN)</label>
                            <input name="team[members][{{ $i }}][name]" value="{{ old("team.members.$i.name", $team[$i]['name'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Name (AR)</label>
                            <input name="team[members][{{ $i }}][name_ar]" value="{{ old("team.members.$i.name_ar", $teamAr[$i]['name'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Role (EN)</label>
                            <input name="team[members][{{ $i }}][role]" value="{{ old("team.members.$i.role", $team[$i]['role'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                            <label class="text-xs font-semibold text-gray-600">Role (AR)</label>
                            <input name="team[members][{{ $i }}][role_ar]" value="{{ old("team.members.$i.role_ar", $teamAr[$i]['role'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">
                            <label class="text-xs font-semibold text-gray-600">Description (EN)</label>
                            <textarea name="team[members][{{ $i }}][desc]" rows="2" class="w-full border rounded px-2 py-1 mb-1">{{ old("team.members.$i.desc", $team[$i]['desc'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Description (AR)</label>
                            <textarea name="team[members][{{ $i }}][desc_ar]" rows="2" class="w-full border rounded px-2 py-1 mb-1" dir="rtl">{{ old("team.members.$i.desc_ar", $teamAr[$i]['desc'] ?? '') }}</textarea>
                            <label class="text-xs font-semibold text-gray-600">Image URL</label>
                            <input name="team[members][{{ $i }}][image]" value="{{ old("team.members.$i.image", $team[$i]['image'] ?? '') }}" class="w-full border rounded px-2 py-1 mb-1">
                        </div>
                    @endfor
                </div>
            </div>

            <div>
                <button type="submit" class="ti-btn ti-btn-outline ti-btn-outline-success rounded-full">Save About Page</button>
            </div>
        </form>
    </div>
</div>
@endsection
