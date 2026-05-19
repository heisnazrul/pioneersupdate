@extends('layouts.admin')

@section('title', 'Application Details')
@section('header', 'View Application')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.applications.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Applications
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.applications.edit', $application) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i> Edit Status
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Applicant Details -->
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Applicant Information</h4>
                <div class="grid grid-cols-2 gap-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Full Name</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $application->first_name }} {{ $application->last_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email Address</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $application->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Phone</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $application->phone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Gender</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ ucfirst($application->gender) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Nationality</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $application->nationality }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Date of Birth</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $application->dob }}</p>
                    </div>
                </div>
            </div>

            <!-- Application Details -->
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Application Specifics</h4>
                <div class="grid grid-cols-1 gap-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Level of Interest</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $application->level_of_interest }}</p>
                    </div>
                    @if($application->notes)
                    <div>
                        <p class="text-gray-500">Applicant Notes</p>
                        <p class="font-medium text-gray-900 dark:text-white mt-1 bg-gray-50 dark:bg-gray-750 p-3 rounded-lg">{{ $application->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Info: Status & Assignment -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-4">
                <h4 class="font-semibold text-gray-800 dark:text-white">Status Tracking</h4>
                <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider">Current Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                        {{ $application->status == 'approved' ? 'bg-green-100 text-green-800' : 
                          ($application->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                          ($application->status == 'reviewing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider">Assigned Counselor</p>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $application->assignedUser ? $application->assignedUser->name : 'Unassigned' }}
                    </p>
                </div>
                @if($application->status_notes)
                <div>
                    <p class="text-xs text-gray-500 mb-1 uppercase tracking-wider">Internal Notes</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 italic">"{{ $application->status_notes }}"</p>
                </div>
                @endif
                <div class="pt-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500">Submitted on: {{ $application->created_at->format('M d, Y H:i') }}</p>
                    <p class="text-xs text-gray-500">Application ID: <span class="font-mono">{{ $application->application_id }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
