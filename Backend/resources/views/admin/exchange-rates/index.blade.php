@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Exchange Rates</h2>
        <div class="space-x-2">
            <form action="{{ route('admin.exchange-rates.refresh-gbp') }}" method="POST" class="inline">
                @csrf
                <button class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success" type="submit">
                    Refresh GBP Rates
                </button>
            </form>
            <a href="{{ route('admin.exchange-rates.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Create</a>
        </div>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pair</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rates as $rate)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono">{{ $rate->base_currency }} → {{ $rate->target_currency }}</td>
                        <td class="px-6 py-4 text-sm">{{ $rate->rate }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $rate->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.exchange-rates.edit', $rate) }}" class="text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.exchange-rates.destroy', $rate) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-danger hover:underline" onclick="return confirm('Delete this rate?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">No rates.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $rates->links() }}</div>
</div>
@endsection
