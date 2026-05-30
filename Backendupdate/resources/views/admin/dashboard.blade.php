@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
@php
    $colorMap = [
        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
        'indigo' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
        'emerald' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-600'],
        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
        'cyan' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-600'],
        'rose' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-600'],
        'amber' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-600'],
        'orange' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600'],
    ];
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
    @foreach($stats as $stat)
        @php $colors = $colorMap[$stat['color']] ?? $colorMap['blue']; @endphp
        <a href="{{ $stat['href'] }}" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 flex items-center hover:border-primary-300 dark:hover:border-primary-600 transition-colors">
            <div class="w-11 h-11 rounded-lg {{ $colors['bg'] }} {{ $colors['text'] }} flex items-center justify-center text-lg mr-4 shrink-0">
                <i class="fa-solid {{ $stat['icon'] }}"></i>
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ $stat['label'] }}</p>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stat['value']) }}</h3>
                @if($stat['total'] !== null)
                    <p class="text-xs text-gray-400 mt-0.5">{{ is_numeric($stat['total']) ? number_format($stat['total']) . ' total' : $stat['total'] }}</p>
                @endif
            </div>
        </a>
    @endforeach
</div>

@if(count($alerts))
    <div class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($alerts as $alert)
            <a href="{{ $alert['href'] }}" class="flex items-center justify-between rounded-xl border border-amber-200 bg-amber-50 dark:bg-amber-900/20 dark:border-amber-800 px-5 py-4 hover:bg-amber-100 dark:hover:bg-amber-900/30 transition-colors">
                <div class="flex items-center gap-3">
                    <i class="fa-solid {{ $alert['icon'] }} text-amber-600"></i>
                    <span class="text-sm font-medium text-amber-900 dark:text-amber-200">{{ $alert['label'] }}</span>
                </div>
                <span class="inline-flex items-center justify-center min-w-[2rem] h-8 px-2 rounded-full bg-amber-600 text-white text-sm font-bold">{{ $alert['count'] }}</span>
            </a>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Bookings</h3>
            <a href="{{ route('admin.course-sat-bookings.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recentBookings as $booking)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $booking->contact_name ?? $booking->reference_no }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $booking->school?->name_en ?? '—' }} · {{ $booking->course?->course_name_from_school ?? 'Course' }}</p>
                    </div>
                    <span class="ml-3 shrink-0 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">No bookings yet.</div>
            @endforelse
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Applications</h3>
            <a href="{{ route('admin.applications.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($recentApplications as $app)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $app->first_name }} {{ $app->last_name }}</p>
                        <p class="text-xs text-gray-500">{{ $app->email }}</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($app->status) }}
                    </span>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">No recent applications found.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-6 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Quick Actions</h3>
    </div>
    <div class="p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('admin.language-schools.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-school-flag text-2xl text-gray-400 mb-2"></i>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add School</span>
        </a>
        <a href="{{ route('admin.blogs.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-pen-to-square text-2xl text-gray-400 mb-2"></i>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Write Blog</span>
        </a>
        <a href="{{ route('admin.countries.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-flag text-2xl text-gray-400 mb-2"></i>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add Country</span>
        </a>
        <a href="{{ route('admin.galleries.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Upload Media</span>
        </a>
    </div>
</div>
@endsection
