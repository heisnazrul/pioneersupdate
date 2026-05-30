@extends('layouts.admin')

@section('title', 'Agent Details')
@section('header', 'Agent Details')

@section('content')
<div class="max-w-5xl">
    @include('admin.affiliates._nav')

    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.agents.index') }}" class="flex items-center gap-1 text-sm text-gray-500 hover:text-primary-600">
            <i class="fa-solid fa-arrow-left"></i> Back to Agents
        </a>
        <a href="{{ route('admin.agents.edit', $agent) }}" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">Edit Agent</a>
    </div>

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Account</h3>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-500">Name</dt><dd class="font-medium text-gray-900 dark:text-white">{{ $agent->user?->name }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd class="text-gray-900 dark:text-white">{{ $agent->user?->email }}</dd></div>
                <div><dt class="text-gray-500">Phone</dt><dd class="text-gray-900 dark:text-white">{{ $agent->user?->phone ?: '—' }}</dd></div>
                <div><dt class="text-gray-500">User Status</dt><dd class="text-gray-900 dark:text-white">{{ ucfirst($agent->user?->status ?? '—') }}</dd></div>
            </dl>
        </div>

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Referral</h3>
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-500">Referral Code</dt><dd class="font-mono font-medium text-gray-900 dark:text-white">{{ $agent->referral_code }}</dd></div>
                <div><dt class="text-gray-500">Custom Code</dt><dd class="text-gray-900 dark:text-white">{{ $agent->is_code_custom ? 'Yes' : 'No' }}</dd></div>
                <div><dt class="text-gray-500">Referral Discount</dt><dd class="text-gray-900 dark:text-white">{{ number_format($agent->referral_discount, 2) }}%</dd></div>
                <div><dt class="text-gray-500">Commission Rate</dt><dd class="text-gray-900 dark:text-white">{{ number_format($agent->commission_percent, 2) }}%</dd></div>
                <div><dt class="text-gray-500">Balance</dt><dd class="text-gray-900 dark:text-white">{{ number_format($agent->commission_balance, 2) }} SAR</dd></div>
                <div><dt class="text-gray-500">Total Earned</dt><dd class="text-gray-900 dark:text-white">{{ number_format($agent->total_commission_earned, 2) }} SAR</dd></div>
                <div><dt class="text-gray-500">Agent Status</dt><dd class="text-gray-900 dark:text-white">{{ ucfirst($agent->status) }}</dd></div>
            </dl>
        </div>
    </div>

    <div class="mt-6 rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Students ({{ $agent->students->count() }})</h3>
        </div>
        @if($agent->students->isEmpty())
            <p class="text-sm text-gray-500">No students linked yet.</p>
        @else
            <div class="space-y-2">
                @foreach($agent->students->take(10) as $student)
                    <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3 text-sm dark:border-gray-700 dark:bg-gray-900/40">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $student->name }}</div>
                        <div class="text-xs text-gray-500">{{ $student->email }}</div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
