@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-xl font-semibold text-gray-800 dark:text-white">Application
                    #{{ $application->application_id }}</h1>
                <p class="text-sm text-gray-500">Submitted on {{ $application->created_at->format('d F Y, h:i A') }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.applications.index') }}" class="ti-btn ti-btn-soft-secondary">
                    <i class="ti ti-arrow-left"></i> Back
                </a>
                <a href="{{ route('admin.applications.edit', $application->id) }}" class="ti-btn ti-btn-primary">
                    <i class="ti ti-pencil"></i> Edit Status
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <!-- Applicant Profile -->
            <div class="col-span-12 xl:col-span-4">
                <div class="box">
                    <div class="box-header">
                        <div class="box-title">Applicant Details</div>
                    </div>
                    <div class="box-body">
                        <div class="text-center mb-6">
                            <span class="avatar avatar-xl rounded-full bg-primary/20 text-primary mb-3 text-2xl">
                                {{ substr($application->first_name, 0, 1) }}
                            </span>
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $application->first_name }}
                                {{ $application->last_name }}</h3>
                            <p class="text-gray-500 text-sm">{{ $application->email }}</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-black/20 rounded-sm">
                                <span class="text-sm text-gray-500">Phone</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white">{{ $application->phone }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-black/20 rounded-sm">
                                <span class="text-sm text-gray-500">Nationality</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white">{{ $application->nationality }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-black/20 rounded-sm">
                                <span class="text-sm text-gray-500">Citizenship</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white">{{ $application->citizenship ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box mt-6">
                    <div class="box-header">
                        <div class="box-title">Status Overview</div>
                    </div>
                    <div class="box-body">
                        <div class="mb-4">
                            <label class="text-xs text-gray-500 uppercase font-semibold">Current Status</label>
                            <div class="mt-1">
                                @php
                                    $statusColor = match ($application->status) {
                                        'accepted' => 'success',
                                        'rejected' => 'danger',
                                        'submitted' => 'info',
                                        'pending' => 'warning',
                                        default => 'primary'
                                    };
                                @endphp
                                <span
                                    class="badge bg-{{ $statusColor }}/10 text-{{ $statusColor }} px-3 py-1.5 rounded-sm text-sm font-medium w-full block text-center">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-xs text-gray-500 uppercase font-semibold">Assigned To</label>
                            <div
                                class="mt-1 flex items-center gap-2 p-2 rounded-sm border border-gray-200 dark:border-white/10">
                                @if($application->assignee)
                                    <span class="avatar avatar-xs rounded-full bg-secondary/20 text-secondary">
                                        {{ substr($application->assignee->name, 0, 1) }}
                                    </span>
                                    <span class="text-sm font-medium">{{ $application->assignee->name }}</span>
                                @else
                                    <span class="text-sm text-gray-400 italic">Unassigned</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Details -->
            <div class="col-span-12 xl:col-span-8">
                <div class="box">
                    <div class="box-header">
                        <div class="box-title">Academic Profile</div>
                    </div>
                    <div class="box-body">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-4 border border-dashed border-gray-200 dark:border-white/10 rounded-sm">
                                <p class="text-xs text-gray-500 uppercase mb-1">Highest Education</p>
                                <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                    {{ $application->highest_education }}</p>
                                <p class="text-sm text-primary">Grade/GPA: {{ $application->grade_average }}</p>
                            </div>
                            <div class="p-4 border border-dashed border-gray-200 dark:border-white/10 rounded-sm">
                                <p class="text-xs text-gray-500 uppercase mb-1">English Proficiency</p>
                                @if($application->has_english_test)
                                    <p class="text-lg font-semibold text-gray-800 dark:text-white">
                                        {{ $application->english_test_type }}</p>
                                    <p class="text-sm text-success">Score: {{ $application->english_test_score }}</p>
                                @else
                                    <p class="text-lg font-semibold text-gray-400">No Test Taken</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box mt-6">
                    <div class="box-header">
                        <div class="box-title">Study Preferences</div>
                    </div>
                    <div class="box-body">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="text-xs text-gray-500 uppercase font-semibold mb-2 block">Destinations</label>
                                <div class="flex flex-wrap gap-2">
                                    @if(is_array($application->destination_interest))
                                        @foreach($application->destination_interest as $dest)
                                            <span
                                                class="badge bg-light text-gray-800 border border-gray-200 py-1 px-2.5 rounded-sm">
                                                {{ $dest }}
                                            </span>
                                        @endforeach
                                    @endif
                                    @if($application->destinations_other)
                                        <span
                                            class="badge bg-light text-gray-800 border border-gray-200 py-1 px-2.5 rounded-sm">
                                            {{ $application->destinations_other }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 uppercase font-semibold mb-2 block">Preferred
                                    Intake</label>
                                <p class="text-sm font-medium">{{ $application->preferred_intake ?: 'Not specified' }}</p>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 uppercase font-semibold mb-2 block">Budget Range</label>
                                <p class="text-sm font-medium text-success">
                                    {{ $application->budget_range ?: 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box mt-6">
                    <div class="box-header">
                        <div class="box-title">Internal Notes</div>
                    </div>
                    <div class="box-body">
                        <div
                            class="p-4 bg-yellow-50 dark:bg-yellow-900/10 rounded-sm border border-yellow-200 dark:border-yellow-900/20">
                            @if($application->status_notes)
                                <p class="text-sm text-gray-700 dark:text-white/80 whitespace-pre-wrap">
                                    {{ $application->status_notes }}</p>
                            @else
                                <p class="text-sm text-gray-400 italic">No notes added by staff yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection