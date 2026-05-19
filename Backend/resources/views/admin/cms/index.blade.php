@php use Illuminate\Support\Str; @endphp
@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS</p>
            <h2 class="text-2xl font-bold">{{ $title }}</h2>
            <p class="text-sm text-gray-500 mt-1">Manage frontend pages for this app.</p>
        </div>
        <div class="flex gap-2">
            @if($app === 'courseenglish')
                <a href="{{ route('admin.cms.course-english.branding.edit') }}" class="ti-btn ti-btn-outline ti-btn-outline-primary">Branding</a>
            @elseif($app === 'university')
                <a href="{{ route('admin.cms.university.branding.edit') }}" class="ti-btn ti-btn-outline ti-btn-outline-primary">Branding</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4">
        @forelse($pages as $page)
            <div class="bg-white shadow rounded-lg p-4 border">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $page->title }}</h3>
                        <p class="text-xs text-gray-500">/{{ $page->slug }}</p>
                    </div>
                    <span class="badge {{ $page->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                        {{ $page->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @php
                    $editRoute = null;
                    if ($app === 'courseenglish' && $page->slug === 'home') {
                        $editRoute = route('admin.cms.course-english.home.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'offers') {
                        $editRoute = route('admin.cms.course-english.offers.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'about-us') {
                        $editRoute = route('admin.cms.course-english.about.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'language-institutes') {
                        $editRoute = route('admin.cms.course-english.language-institutes.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'language-institute-detail') {
                        $editRoute = route('admin.cms.course-english.language-institute-detail.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'articles') {
                        $editRoute = route('admin.cms.course-english.articles.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'contact-us') {
                        $editRoute = route('admin.cms.course-english.contact.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'travel-and-tourism') {
                        $editRoute = route('admin.cms.course-english.travel.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'university-admissions') {
                        $editRoute = route('admin.cms.course-english.university-admissions.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'compare') {
                        $editRoute = route('admin.cms.course-english.compare.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'wishlist') {
                        $editRoute = route('admin.cms.course-english.wishlist.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'online-courses') {
                        $editRoute = route('admin.cms.course-english.online-courses.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'summer-programs') {
                        $editRoute = route('admin.cms.course-english.summer-programs.edit');
                    } elseif ($app === 'courseenglish' && $page->slug === 'training-and-professional-courses') {
                        $editRoute = route('admin.cms.course-english.training.edit');
                    } elseif ($app === 'university' && $page->slug === 'home') {
                        $editRoute = route('admin.cms.university.home.edit');
                    } elseif ($app === 'university' && $page->slug === 'about') {
                        $editRoute = route('admin.cms.university.about.edit');
                    } elseif ($app === 'university' && $page->slug === 'contact') {
                        $editRoute = route('admin.cms.university.contact.edit');
                    } elseif ($app === 'university' && $page->slug === 'services') {
                        $editRoute = route('admin.cms.university.services.edit');
                    } elseif ($app === 'university' && $page->slug === 'visa-support') {
                        $editRoute = route('admin.cms.university.visa.edit');
                    } elseif ($app === 'university' && $page->slug === 'agents') {
                        $editRoute = route('admin.cms.university.agents.edit');
                    } elseif ($app === 'university' && $page->slug === 'accommodation') {
                        $editRoute = route('admin.cms.university.accommodation.edit');
                    } elseif ($app === 'university' && $page->slug === 'applications') {
                        $editRoute = route('admin.cms.university.applications.edit');
                    } elseif ($app === 'university' && $page->slug === 'application') {
                        $editRoute = route('admin.cms.university.application.edit');
                    } elseif ($app === 'university' && $page->slug === 'student-guide') {
                        $editRoute = route('admin.cms.university.student-guide.edit');
                    }
                @endphp
                <div class="mt-3 flex gap-2">
                    @if($editRoute)
                        <a href="{{ $editRoute }}" class="ti-btn ti-btn-outline ti-btn-outline-primary ti-btn-sm">Edit</a>
                    @else
                        <span class="text-xs text-gray-400">Custom editor not wired yet</span>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-500">No pages yet.</p>
        @endforelse
    </div>
</div>
@endsection
