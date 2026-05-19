@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
        <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xl mr-4">
            <i class="fa-solid fa-file-signature"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Applications</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Application::count() }}</h3>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
        <div class="w-12 h-12 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl mr-4">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Students</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\User::whereIn('role', ['lg_student', 'uni_student'])->count() }}</h3>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
        <div class="w-12 h-12 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xl mr-4">
            <i class="fa-solid fa-newspaper"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Published Blogs</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Blog::count() }}</h3>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
        <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center text-xl mr-4">
            <i class="fa-solid fa-envelope"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Inquiries</p>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\ContactSubmission::where('status', 'pending')->count() }}</h3>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Applications -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Applications</h3>
            <a href="{{ route('admin.applications.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">View All</a>
        </div>
        <div class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse(\App\Models\Application::with('user')->orderByDesc('created_at')->limit(5)->get() as $app)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $app->first_name }} {{ $app->last_name }}</p>
                        <p class="text-xs text-gray-500">{{ $app->email }}</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($app->status) }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    No recent applications found.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Quick Actions</h3>
        </div>
        <div class="p-6 grid grid-cols-2 gap-4">
            <a href="{{ route('admin.blogs.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-pen-to-square text-2xl text-gray-400 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Write Blog</span>
            </a>
            <a href="{{ route('admin.users.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-user-plus text-2xl text-gray-400 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add User</span>
            </a>
            <a href="{{ route('admin.countries.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-map-location-dot text-2xl text-gray-400 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Add Country</span>
            </a>
            <a href="{{ route('admin.galleries.create') }}" class="flex flex-col items-center justify-center p-4 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-cloud-arrow-up text-2xl text-gray-400 mb-2"></i>
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Upload Media</span>
            </a>
        </div>
    </div>
</div>
@endsection
