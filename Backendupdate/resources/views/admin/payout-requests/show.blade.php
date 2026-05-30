@extends('layouts.admin')

@section('title', 'Payout ' . $payout->reference_no)
@section('header', 'Payout Request')

@section('content')
<div class="max-w-4xl">
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
    @endif

    <div class="mb-4 flex items-center justify-between">
        <div>
            <p class="font-mono text-lg text-gray-900 dark:text-white">{{ $payout->reference_no }}</p>
            <p class="text-sm text-gray-500">{{ ucfirst($payout->referrer_type) }} · {{ ucfirst($payout->status) }}</p>
        </div>
        <a href="{{ route('admin.payout-requests.edit', $payout) }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm text-white">Update</a>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-sm font-semibold uppercase text-gray-500">Requester</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">Name</dt><dd class="text-gray-900 dark:text-white">{{ $payout->user?->name }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd class="text-gray-900 dark:text-white">{{ $payout->user?->email }}</dd></div>
                <div><dt class="text-gray-500">Amount</dt><dd class="text-gray-900 dark:text-white">{{ number_format($payout->amount, 2) }} {{ $payout->currency }}</dd></div>
                <div><dt class="text-gray-500">Requested</dt><dd class="text-gray-900 dark:text-white">{{ $payout->created_at?->format('Y-m-d H:i') }}</dd></div>
            </dl>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-sm font-semibold uppercase text-gray-500">Bank details (snapshot)</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-gray-500">Account name</dt><dd class="text-gray-900 dark:text-white">{{ $payout->bank_account_name ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Bank</dt><dd class="text-gray-900 dark:text-white">{{ $payout->bank_name ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">Account number</dt><dd class="text-gray-900 dark:text-white">{{ $payout->bank_account_number ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">IBAN</dt><dd class="text-gray-900 dark:text-white">{{ $payout->bank_iban ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">SWIFT</dt><dd class="text-gray-900 dark:text-white">{{ $payout->bank_swift_code ?: '—' }}</dd></div>
            </dl>
        </div>
    </div>

    @if($payout->user_notes || $payout->admin_notes)
        <div class="mt-6 grid gap-6 md:grid-cols-2">
            @if($payout->user_notes)
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-2 text-sm font-semibold uppercase text-gray-500">User notes</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $payout->user_notes }}</p>
                </div>
            @endif
            @if($payout->admin_notes)
                <div class="rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-2 text-sm font-semibold uppercase text-gray-500">Admin notes</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $payout->admin_notes }}</p>
                </div>
            @endif
        </div>
    @endif

    <div class="mt-6">
        <a href="{{ route('admin.payout-requests.index') }}" class="text-sm text-primary-600 hover:underline">← Back to payouts</a>
    </div>
</div>
@endsection
