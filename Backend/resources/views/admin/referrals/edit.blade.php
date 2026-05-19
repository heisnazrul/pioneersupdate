@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Edit Agent Referral</h2>
        <a href="{{ route('admin.referrals.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back</a>
    </div>

    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <div>
            <div class="text-lg font-semibold">{{ $agent->user->name ?? 'Agent' }}</div>
            <div class="text-sm text-gray-500">{{ $agent->user->email ?? '' }}</div>
        </div>

        <form action="{{ route('admin.referrals.update', $agent) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Referral Code</label>
                    <input type="text" name="referral_code" value="{{ old('referral_code', $agent->referral_code) }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    @php $status = old('status', $agent->status); @endphp
                    <select name="status" class="mt-1 w-full border rounded px-3 py-2">
                        @foreach(['pending','approved','rejected'] as $s)
                            <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Referral Discount (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="referral_discount" value="{{ old('referral_discount', $agent->referral_discount) }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Percent (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_percent" value="{{ old('commission_percent', $agent->commission_percent) }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Save</button>
                <a href="{{ route('admin.referrals.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
