@extends('layouts.admin')

@section('title', 'Scholarship Application')
@section('header', 'Scholarship Application')

@section('content')
<div class="space-y-6 max-w-4xl">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div><dt class="font-medium text-gray-500">Application ID</dt><dd>{{ $scholarshipApplication->application_id }}</dd></div>
            <div><dt class="font-medium text-gray-500">Scholarship</dt><dd>{{ $scholarshipApplication->scholarship_title ?: '-' }}</dd></div>
            <div><dt class="font-medium text-gray-500">First Name</dt><dd>{{ $scholarshipApplication->first_name }}</dd></div>
            <div><dt class="font-medium text-gray-500">Last Name</dt><dd>{{ $scholarshipApplication->last_name }}</dd></div>
            <div><dt class="font-medium text-gray-500">Email</dt><dd>{{ $scholarshipApplication->email }}</dd></div>
            <div><dt class="font-medium text-gray-500">Phone</dt><dd>{{ $scholarshipApplication->phone }}</dd></div>
            <div><dt class="font-medium text-gray-500">Country</dt><dd>{{ $scholarshipApplication->country ?: '-' }}</dd></div>
            <div><dt class="font-medium text-gray-500">City</dt><dd>{{ $scholarshipApplication->city ?: '-' }}</dd></div>
            <div><dt class="font-medium text-gray-500">Education Level</dt><dd>{{ $scholarshipApplication->education_level ?: '-' }}</dd></div>
            <div><dt class="font-medium text-gray-500">Grade Average</dt><dd>{{ $scholarshipApplication->grade_average ?: '-' }}</dd></div>
            <div><dt class="font-medium text-gray-500">English Proficiency</dt><dd>{{ $scholarshipApplication->english_proficiency ?: '-' }}</dd></div>
            <div><dt class="font-medium text-gray-500">Status</dt><dd>{{ ucfirst($scholarshipApplication->status) }}</dd></div>
        </dl>
        @if($scholarshipApplication->notes)
            <div class="mt-6">
                <h4 class="font-medium text-gray-500 mb-2">Notes</h4>
                <p class="text-sm">{{ $scholarshipApplication->notes }}</p>
            </div>
        @endif
    </div>

    <div class="flex gap-3">
        <a href="{{ route('admin.scholarship-applications.edit', $scholarshipApplication) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">Edit Application</a>
        <a href="{{ route('admin.scholarship-applications.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Back</a>
    </div>
</div>
@endsection
