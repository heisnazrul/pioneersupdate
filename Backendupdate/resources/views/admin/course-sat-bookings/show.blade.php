@extends('layouts.admin')

@section('title', 'Booking ' . $booking->reference_no)
@section('header', 'Booking Details')

@section('content')
<div class="max-w-5xl">
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex items-center justify-between">
        <div>
            <p class="font-mono text-lg text-gray-900 dark:text-white">{{ $booking->reference_no }}</p>
            <p class="text-sm text-gray-500">{{ str_replace('_', ' ', $bookingType) }} · {{ ucfirst($booking->status) }}</p>
        </div>
        <a href="{{ route('admin.course-sat-bookings.edit', ['type' => $bookingType, 'id' => $booking->id]) }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm text-white">Update status</a>
        @if(Route::has('team.bookings.show'))
            <a href="{{ route('team.bookings.show', ['type' => $bookingType, 'id' => $booking->id]) }}" class="rounded-lg border border-primary-600 px-4 py-2 text-sm text-primary-600">Open in Team panel</a>
        @endif
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-sm font-semibold uppercase text-gray-500">Contact</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">Name</dt><dd class="text-gray-900 dark:text-white">{{ $booking->contact_name }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd class="text-gray-900 dark:text-white">{{ $booking->contact_email }}</dd></div>
                <div><dt class="text-gray-500">Phone</dt><dd class="text-gray-900 dark:text-white">{{ $booking->contact_phone ?: '—' }}</dd></div>
            </dl>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-sm font-semibold uppercase text-gray-500">Booking</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">School</dt><dd class="text-gray-900 dark:text-white">{{ $booking->school?->name_en ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Start date</dt><dd class="text-gray-900 dark:text-white">{{ optional($booking->start_date)->format('Y-m-d') ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Weeks</dt><dd class="text-gray-900 dark:text-white">{{ $booking->weeks ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Total</dt><dd class="text-gray-900 dark:text-white">{{ number_format((float) $booking->total_amount, 2) }} {{ $booking->display_currency }}</dd></div>
                @if($booking->bookedByAgent)
                    <div><dt class="text-gray-500">Booked by agent</dt><dd class="text-gray-900 dark:text-white">{{ $booking->bookedByAgent->user?->name }}</dd></div>
                @endif
            </dl>
        </div>
    </div>

    @if($booking->notes)
        <div class="mt-6 rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-2 text-sm font-semibold uppercase text-gray-500">Notes</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $booking->notes }}</p>
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.course-sat-bookings.index') }}" class="text-sm text-primary-600 hover:underline">← Back to bookings</a>
    </div>
</div>
@endsection
