@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Language School Discounts</h2>
        <a href="{{ route('admin.language-school-discounts.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Create Discount
        </a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Percent</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dates</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($discounts as $discount)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            {{ $discount->name }}<br>
                            <span class="text-xs text-gray-500">{{ $discount->ar_name }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($discount->discount_percentage, 2) }}%</td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ optional($discount->start_date)->format('Y-m-d') ?? '—' }} –
                            {{ optional($discount->end_date)->format('Y-m-d') ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="badge {{ $discount->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                                {{ $discount->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.language-school-discounts.edit', ['ls_discount' => $discount->id]) }}" class="text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.language-school-discounts.destroy', ['ls_discount' => $discount->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger hover:underline" onclick="return confirm('Delete this discount?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-sm text-gray-500 text-center">No discounts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $discounts->links() }}
    </div>
</div>
@endsection
