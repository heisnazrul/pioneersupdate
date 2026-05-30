@extends('layouts.admin')

@section('title', 'Referral Commissions')
@section('header', 'Referral Commissions')

@section('content')
<div class="max-w-7xl">
    @include('admin.affiliates._nav')

    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.referral-commissions.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ !$activeStatus ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">All</a>
            @foreach($statuses as $status)
                <a href="{{ route('admin.referral-commissions.index', ['status' => $status]) }}" class="rounded-lg px-4 py-2 text-sm font-medium {{ $activeStatus === $status ? 'bg-primary-600 text-white' : 'border border-gray-200 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">{{ ucfirst($status) }}</a>
            @endforeach
        </div>
        <form method="GET" class="flex flex-wrap gap-2">
            @if($activeStatus)<input type="hidden" name="status" value="{{ $activeStatus }}">@endif
            <select name="referrer_type" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">All referrers</option>
                <option value="student" @selected($activeReferrerType === 'student')>Student</option>
                <option value="agent" @selected($activeReferrerType === 'agent')>Agent</option>
            </select>
            <input type="search" name="search" value="{{ $search }}" placeholder="Booking ref or email..." class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm dark:bg-gray-700">Filter</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Booking</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Referrer</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Referred User</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Commission</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($commissions as $commission)
                    <tr>
                        <td class="px-4 py-3 text-sm">
                            <div class="font-mono text-gray-900 dark:text-white">{{ $commission->booking_reference }}</div>
                            <div class="text-xs text-gray-500">{{ str_replace('_', ' ', $commission->booking_type) }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                            {{ ucfirst($commission->referrer_type) }}
                            @if($commission->referrer_type === 'agent' && $commission->referrerAgent)
                                <div class="text-xs text-gray-500">{{ $commission->referrerAgent->user?->name }}</div>
                            @elseif($commission->referrerUser)
                                <div class="text-xs text-gray-500">{{ $commission->referrerUser->name }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                            {{ $commission->referredUser?->name ?? '—' }}
                            <div class="text-xs text-gray-500">{{ $commission->referredUser?->email }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                            {{ number_format($commission->commission_amount, 2) }} {{ $commission->currency }}
                            <div class="text-xs text-gray-500">{{ number_format($commission->commission_percent, 2) }}%</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $commission->status === 'paid' ? 'bg-green-100 text-green-700' : ($commission->status === 'approved' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($commission->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.referral-commissions.show', $commission) }}" class="p-1 text-gray-400 hover:text-primary-600"><i class="fa-solid fa-eye"></i></a>
                            <a href="{{ route('admin.referral-commissions.edit', $commission) }}" class="p-1 text-gray-400 hover:text-primary-600"><i class="fa-solid fa-pen-to-square"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No commissions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $commissions->links() }}</div>
</div>
@endsection
