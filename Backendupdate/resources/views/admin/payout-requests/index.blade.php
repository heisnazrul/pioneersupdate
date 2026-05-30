@extends('layouts.admin')

@section('title', 'Payout Requests')
@section('header', 'Payout Requests')

@section('content')
<div class="max-w-7xl">
    @include('admin.affiliates._nav')

    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.payout-requests.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ !$activeStatus ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">All</a>
            @foreach($statuses as $status)
                <a href="{{ route('admin.payout-requests.index', ['status' => $status]) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeStatus === $status ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">{{ ucfirst($status) }}</a>
            @endforeach
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            @if($activeStatus)<input type="hidden" name="status" value="{{ $activeStatus }}">@endif
            <select name="referrer_type" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">All types</option>
                <option value="student" @selected($activeReferrerType === 'student')>Student</option>
                <option value="agent" @selected($activeReferrerType === 'agent')>Agent</option>
            </select>
            <input type="search" name="search" value="{{ $search }}" placeholder="Reference or email..." class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm dark:bg-gray-700">Filter</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Reference</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">User</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Amount</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($payouts as $payout)
                    <tr>
                        <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-white">{{ $payout->reference_no }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                            {{ $payout->user?->name }}
                            <div class="text-xs text-gray-500">{{ $payout->user?->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ ucfirst($payout->referrer_type) }}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ number_format($payout->amount, 2) }} {{ $payout->currency }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $payout->status === 'paid' ? 'bg-green-100 text-green-700' : ($payout->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($payout->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.payout-requests.show', $payout) }}" class="p-1 text-gray-400 hover:text-primary-600"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ route('admin.payout-requests.edit', $payout) }}" class="p-1 text-gray-400 hover:text-primary-600"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No payout requests found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payouts->links() }}</div>
</div>
@endsection
