@extends('layouts.admin')

@section('title', 'Commission Details')
@section('header', 'Commission Details')

@section('content')
<div class="max-w-4xl">
    @include('admin.affiliates._nav')

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.referral-commissions.index') }}" class="flex items-center gap-1 text-sm text-gray-500 hover:text-primary-600">
            <i class="fa-solid fa-arrow-left"></i> Back to Commissions
        </a>
        <a href="{{ route('admin.referral-commissions.edit', $commission) }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">Edit</a>
    </div>

    <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <dl class="grid gap-4 md:grid-cols-2 text-sm">
            <div><dt class="text-gray-500">Booking Reference</dt><dd class="font-mono font-medium text-gray-900 dark:text-white">{{ $commission->booking_reference }}</dd></div>
            <div><dt class="text-gray-500">Booking Type</dt><dd class="text-gray-900 dark:text-white">{{ $commission->booking_type }}</dd></div>
            <div><dt class="text-gray-500">Booking Total</dt><dd class="text-gray-900 dark:text-white">{{ number_format($commission->booking_total, 2) }} {{ $commission->currency }}</dd></div>
            <div><dt class="text-gray-500">Discount Given</dt><dd class="text-gray-900 dark:text-white">{{ number_format($commission->discount_given, 2) }} {{ $commission->currency }}</dd></div>
            <div><dt class="text-gray-500">Commission</dt><dd class="text-gray-900 dark:text-white">{{ number_format($commission->commission_amount, 2) }} {{ $commission->currency }} ({{ number_format($commission->commission_percent, 2) }}%)</dd></div>
            <div><dt class="text-gray-500">Status</dt><dd class="text-gray-900 dark:text-white">{{ ucfirst($commission->status) }}</dd></div>
            <div><dt class="text-gray-500">Referrer Type</dt><dd class="text-gray-900 dark:text-white">{{ ucfirst($commission->referrer_type) }}</dd></div>
            <div><dt class="text-gray-500">Referrer</dt><dd class="text-gray-900 dark:text-white">
                @if($commission->referrer_type === 'agent')
                    {{ $commission->referrerAgent?->user?->name ?? 'Agent #'.$commission->referrer_agent_id }}
                @else
                    {{ $commission->referrerUser?->name ?? '—' }}
                @endif
            </dd></div>
            <div><dt class="text-gray-500">Referred User</dt><dd class="text-gray-900 dark:text-white">{{ $commission->referredUser?->name }} ({{ $commission->referredUser?->email }})</dd></div>
            <div><dt class="text-gray-500">Payable At</dt><dd class="text-gray-900 dark:text-white">{{ $commission->payable_at?->format('Y-m-d H:i') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Paid At</dt><dd class="text-gray-900 dark:text-white">{{ $commission->paid_at?->format('Y-m-d H:i') ?? '—' }}</dd></div>
            <div><dt class="text-gray-500">Created</dt><dd class="text-gray-900 dark:text-white">{{ $commission->created_at->format('Y-m-d H:i') }}</dd></div>
        </dl>
    </div>
</div>
@endsection
