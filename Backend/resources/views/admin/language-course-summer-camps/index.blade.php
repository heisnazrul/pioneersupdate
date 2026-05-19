@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Summer Camps</h2>
        <a href="{{ route('admin.language-course-summer-camps.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Create</a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thumb</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($camps as $camp)
                    <tr>
                        <td class="px-6 py-4 text-sm">
                            @if($camp->thumbnail)
                                <img src="{{ asset('storage/' . $camp->thumbnail) }}" alt="Thumb" class="h-10 w-14 object-cover rounded border">
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $camp->name }}</td>
                        <td class="px-6 py-4 text-sm">{{ $camp->branch->school->name ?? '—' }} / {{ $camp->branch->slug ?? '' }}</td>
                        <td class="px-6 py-4 text-sm">{{ number_format($camp->fee_amount,2) }}</td>
                        <td class="px-6 py-4 text-sm">{{ ucfirst($camp->status) }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.language-course-summer-camps.edit', $camp) }}" class="text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.language-course-summer-camps.destroy', $camp) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-danger hover:underline" onclick="return confirm('Delete this camp?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">No camps.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $camps->links() }}</div>
</div>
@endsection
