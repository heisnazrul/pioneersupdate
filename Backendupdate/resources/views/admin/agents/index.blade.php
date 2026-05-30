@extends('layouts.admin')

@section('title', 'Agents')
@section('header', 'Affiliate Agents')

@section('content')
<div class="max-w-7xl">
    @include('admin.affiliates._nav')

    <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <form method="GET" class="flex flex-wrap items-center gap-2">
            <input type="search" name="search" value="{{ $search }}" placeholder="Search name, email, code..."
                   class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                <option value="">All statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected($activeStatus === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium dark:bg-gray-700">Filter</button>
        </form>
        <a href="{{ route('admin.agents.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700">
            <i class="fa-solid fa-plus"></i> Add Agent
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Agent</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Referral Code</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Commission</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Balance</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($agents as $agent)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $agent->user?->name ?? '—' }}</div>
                            <div class="text-xs text-gray-500">{{ $agent->user?->email }}</div>
                            @if($agent->company_name)
                                <div class="text-xs text-gray-400">{{ $agent->company_name }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 font-mono text-sm text-gray-700 dark:text-gray-300">{{ $agent->referral_code }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ number_format($agent->commission_percent, 2) }}%</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ number_format($agent->commission_balance, 2) }} SAR</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium
                                {{ $agent->status === 'active' ? 'bg-green-100 text-green-700' : ($agent->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                {{ ucfirst($agent->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.agents.show', $agent) }}" class="p-1 text-gray-400 hover:text-primary-600"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('admin.agents.edit', $agent) }}" class="p-1 text-gray-400 hover:text-primary-600"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form action="{{ route('admin.agents.destroy', $agent) }}" method="POST" onsubmit="return confirm('Delete this agent and linked user account?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 text-gray-400 hover:text-red-600"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No agents found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $agents->links() }}</div>
</div>
@endsection
