@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Language School Coupons</h2>
        <a href="{{ route('admin.language-school-coupons.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Create Coupon
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono text-gray-900">{{ $coupon->code }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $coupon->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($coupon->discount_type) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ number_format($coupon->discount_value, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $coupon->used_count }} / {{ $coupon->usage_limit }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="badge {{ $coupon->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.language-school-coupons.edit', $coupon) }}" class="text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.language-school-coupons.destroy', $coupon) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:underline" onclick="return confirm('Delete this coupon?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-sm text-gray-500 text-center">No coupons found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $coupons->links() }}
    </div>
</div>
@endsection
