@extends('layouts.admin')

@section('title', 'Attribution Details')
@section('header', 'Attribution Details')

@section('content')
<div class="max-w-4xl">
    @include('admin.affiliates._nav')

    <div class="mb-6">
        <a href="{{ route('admin.referral-attributions.index') }}" class="flex items-center gap-1 text-sm text-gray-500 hover:text-primary-600">
            <i class="fa-solid fa-arrow-left"></i> Back to Attributions
        </a>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <dl class="grid gap-4 md:grid-cols-2 text-sm">
            <div><dt class="text-gray-500">Referred User</dt><dd class="text-gray-900 dark:text-white">{{ $attribution->referredUser?->name }} ({{ $attribution->referredUser?->email }})</dd></div>
            <div><dt class="text-gray-500">Referrer Type</dt><dd class="text-gray-900 dark:text-white">{{ ucfirst($attribution->referrer_type) }}</dd></div>
            <div><dt class="text-gray-500">Referrer</dt><dd class="text-gray-900 dark:text-white">
                @if($attribution->referrer_type === 'agent')
                    {{ $attribution->referrerAgent?->user?->name ?? 'Agent #'.$attribution->referrer_agent_id }}
                @else
                    {{ $attribution->referrerUser?->name ?? '—' }}
                @endif
            </dd></div>
            <div><dt class="text-gray-500">Referral Code</dt><dd class="font-mono text-gray-900 dark:text-white">{{ $attribution->referral_code }}</dd></div>
            <div><dt class="text-gray-500">Source</dt><dd class="text-gray-900 dark:text-white">{{ $attribution->source }}</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd class="text-gray-900 dark:text-white">{{ ucfirst($attribution->status) }}</dd></div>
            <div><dt class="text-gray-500">Qualified At</dt><dd class="text-gray-900 dark:text-white">{{ $attribution->qualified_at?->format('Y-m-d H:i') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">First Booking</dt><dd class="text-gray-900 dark:text-white">{{ $attribution->first_booking_type ? $attribution->first_booking_type.' #'.$attribution->first_booking_id : '—' }}</dd></div>
            <div><dt class="text-gray-500">Created</dt><dd class="text-gray-900 dark:text-white">{{ $attribution->created_at->format('Y-m-d H:i') }}</dd></div>
        </dl>
    </div>
</div>
@endsection
