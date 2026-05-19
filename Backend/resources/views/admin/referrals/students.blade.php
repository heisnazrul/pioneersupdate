@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Referred Students</h2>
        <a href="{{ route('admin.referrals.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Agents</a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Agent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Onboarding</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($referrals as $ref)
                    <tr>
                        <td class="px-6 py-4 text-sm">
                            {{ $ref->name }}<br>
                            <span class="text-xs text-gray-500">{{ $ref->email }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ $ref->agent->user->name ?? '—' }}<br>
                            <span class="text-xs text-gray-500">{{ $ref->agent->referral_code ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $ref->phone ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $ref->country ?? '—' }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if($ref->onboarded_at)
                                <span class="badge bg-success/10 text-success">Onboarded {{ $ref->onboarded_at->format('Y-m-d') }}</span>
                            @elseif($ref->onboarding_token)
                                <span class="badge bg-warning/10 text-warning">Invited</span>
                            @else
                                <span class="badge bg-gray-100 text-gray-600">Pending</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">No referred students.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $referrals->links() }}
    </div>
</div>
@endsection
