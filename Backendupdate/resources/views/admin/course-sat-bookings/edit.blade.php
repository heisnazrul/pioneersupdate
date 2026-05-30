@extends('layouts.admin')

@section('title', 'Edit Booking')
@section('header', 'Update Booking Status')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.course-sat-bookings.update', ['type' => $bookingType, 'id' => $booking->id]) }}" class="space-y-6 rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Reference</label>
            <p class="font-mono text-gray-900 dark:text-white">{{ $booking->reference_no }}</p>
        </div>

        <div>
            <label for="status" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select id="status" name="status" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $booking->status) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="assigned_to" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Assigned to</label>
            <select id="assigned_to" name="assigned_to" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">Unassigned</option>
                @foreach($staff as $member)
                    <option value="{{ $member->id }}" @selected((string) old('assigned_to', $booking->assigned_to) === (string) $member->id)>{{ $member->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="notes" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Internal notes</label>
            <textarea id="notes" name="notes" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('notes', $booking->notes) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm text-white">Save changes</button>
            <a href="{{ route('admin.course-sat-bookings.show', ['type' => $bookingType, 'id' => $booking->id]) }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm text-gray-700 dark:border-gray-600 dark:text-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection
