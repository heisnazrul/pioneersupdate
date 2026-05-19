@extends('layouts.admin')

@section('title', 'Update Application Status')
@section('header', 'Update Application')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.applications.show', $application) }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Application
        </a>
    </div>

    <form action="{{ route('admin.applications.update', $application) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Application Status *</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @foreach($statuses as $status)
                        <label class="relative flex flex-col items-center justify-center p-3 border rounded-lg cursor-pointer transition-all {{ $application->status == $status ? 'bg-primary-50 border-primary-500 text-primary-700' : 'bg-white dark:bg-gray-750 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-primary-300' }}">
                            <input type="radio" name="status" value="{{ $status }}" class="sr-only" {{ $application->status == $status ? 'checked' : '' }}>
                            <span class="text-sm font-semibold">{{ ucfirst($status) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assign to Counselor</label>
                <select name="assigned_to" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">-- Unassigned --</option>
                    @foreach($counsellors as $counselor)
                        <option value="{{ $counselor->id }}" {{ old('assigned_to', $application->assigned_to) == $counselor->id ? 'selected' : '' }}>{{ $counselor->name }} ({{ $counselor->role }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Internal Status Notes</label>
                <textarea name="status_notes" rows="4" placeholder="Mention updates, missing documents, or reasons for rejection/approval..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">{{ old('status_notes', $application->status_notes) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.applications.show', $application) }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-2 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
