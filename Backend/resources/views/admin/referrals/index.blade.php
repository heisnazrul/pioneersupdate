@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Agent Referrals</h2>
        <a href="{{ route('admin.referrals.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Assign Agent</a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Referral Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount / Commission</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($agents as $agent)
                    <tr>
                        <td class="px-6 py-4 text-sm">
                            {{ $agent->user->name ?? '—' }}<br>
                            <span class="text-xs text-gray-500">{{ $agent->user->email ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $agent->company_name ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm font-mono">{{ $agent->referral_code }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{ $agent->referral_discount }}% / {{ $agent->commission_percent }}%
                        </td>
                        <td class="px-6 py-4 text-sm">{{ ucfirst($agent->status) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ optional($agent->referral_joined_at)->format('Y-m-d') ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.referrals.edit', $agent) }}" class="text-primary hover:underline">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-4 text-sm text-center text-gray-500">No agents found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $agents->links() }}
    </div>
</div>
@endsection
