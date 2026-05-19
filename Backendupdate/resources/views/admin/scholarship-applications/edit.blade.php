@extends('layouts.admin')

@section('title', 'Edit Scholarship Application')
@section('header', 'Edit Scholarship Application')

@section('content')
<div class="max-w-4xl space-y-6">
    <form action="{{ route('admin.scholarship-applications.update', $scholarshipApplication) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium mb-1">Status *</label>
                <select name="status" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    @foreach(['pending', 'reviewing', 'approved', 'rejected'] as $status)
                        <option value="{{ $status }}" {{ old('status', $scholarshipApplication->status) === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Assignee</label>
                <select name="assignee_id" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                    <option value="">Unassigned</option>
                    @foreach($assignees as $assignee)
                        <option value="{{ $assignee->id }}" {{ (string) old('assignee_id', $scholarshipApplication->assignee_id) === (string) $assignee->id ? 'selected' : '' }}>{{ $assignee->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="6" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">{{ old('notes', $scholarshipApplication->notes) }}</textarea>
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">Update Application</button>
            <a href="{{ route('admin.scholarship-applications.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
