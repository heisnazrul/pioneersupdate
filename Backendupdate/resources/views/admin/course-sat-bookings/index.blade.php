@extends('layouts.admin')

@section('title', 'CourseSat Bookings')
@section('header', 'CourseSat Bookings')

@section('content')
<div class="max-w-7xl">
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.course-sat-bookings.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeType === 'all' && !$activeStatus ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">All</a>
            <a href="{{ route('admin.course-sat-bookings.index', ['type' => 'language']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeType === 'language' ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">Language</a>
            <a href="{{ route('admin.course-sat-bookings.index', ['type' => 'online']) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeType === 'online' ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">Online</a>
            @foreach($statuses as $status)
                <a href="{{ route('admin.course-sat-bookings.index', array_filter(['status' => $status, 'type' => $activeType !== 'all' ? $activeType : null])) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeStatus === $status ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">{{ ucfirst($status) }}</a>
            @endforeach
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            @if($activeType && $activeType !== 'all')<input type="hidden" name="type" value="{{ $activeType }}">@endif
            @if($activeStatus)<input type="hidden" name="status" value="{{ $activeStatus }}">@endif
            <input type="search" name="search" value="{{ $search }}" placeholder="Reference, name, email..." class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm dark:bg-gray-700">Filter</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Reference</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Student</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">School</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($bookings as $booking)
                    <tr>
                        <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-white">{{ $booking['reference_no'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ str_replace('_', ' ', $booking['type']) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                            {{ $booking['contact_name'] }}
                            <div class="text-xs text-gray-500">{{ $booking['contact_email'] }}</div>
                            @if($booking['booked_by_agent'])
                                <div class="text-xs text-blue-600">Agent: {{ $booking['booked_by_agent'] }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $booking['school_name'] ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ number_format($booking['total_amount'], 2) }} {{ $booking['display_currency'] }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst($booking['status']) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.course-sat-bookings.show', ['type' => $booking['type'], 'id' => $booking['id']]) }}" class="p-1 text-gray-400 hover:text-primary-600"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ route('admin.course-sat-bookings.edit', ['type' => $booking['type'], 'id' => $booking['id']]) }}" class="p-1 text-gray-400 hover:text-primary-600"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-sm text-gray-500">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $bookings->links() }}</div>
</div>
@endsection
