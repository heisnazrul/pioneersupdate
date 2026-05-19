@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Assign Agent</h2>
        <a href="{{ route('admin.referrals.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back</a>
    </div>

    <div class="bg-white shadow rounded-lg p-6 space-y-4">
        <form action="{{ route('admin.referrals.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">User</label>
                    <select name="user_id" class="mt-1 w-full border rounded px-3 py-2" required>
                        <option value="">Select user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }}) — {{ $user->role }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Company</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Referral Discount (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="referral_discount" value="{{ old('referral_discount', 0) }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Commission Percent (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_percent" value="{{ old('commission_percent', 0) }}" class="mt-1 w-full border rounded px-3 py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    @php $status = old('status', 'pending'); @endphp
                    <select name="status" class="mt-1 w-full border rounded px-3 py-2">
                        @foreach(['pending','approved','rejected'] as $s)
                            <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Assign</button>
                <a href="{{ route('admin.referrals.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
