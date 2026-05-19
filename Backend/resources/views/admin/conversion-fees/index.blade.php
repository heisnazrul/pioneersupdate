@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Conversion Fees</h2>
        <a href="{{ route('admin.conversion-fees.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Create</a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pair</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee (%)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($fees as $fee)
                    <tr>
                        <td class="px-6 py-4 text-sm font-mono">{{ $fee->base_currency }} → {{ $fee->target_currency }}</td>
                        <td class="px-6 py-4 text-sm">{{ $fee->fee }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $fee->updated_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.conversion-fees.edit', $fee) }}" class="text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.conversion-fees.destroy', $fee) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-danger hover:underline" onclick="return confirm('Delete this fee?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-6 py-4 text-sm text-center text-gray-500">No conversion fees.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $fees->links() }}</div>
</div>
@endsection
