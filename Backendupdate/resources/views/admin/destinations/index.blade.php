@extends('layouts.admin')

@section('title', 'Destinations')
@section('header', 'Destinations')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Destinations</h3>
    <a href="{{ route('admin.destinations.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Add Destination</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Destination</th>
                <th class="px-6 py-4">Country</th>
                <th class="px-6 py-4">Universities</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($destinations as $destination)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-10 rounded border bg-white overflow-hidden flex items-center justify-center">
                                @if($destination->image_url)
                                    <img src="{{ Storage::url($destination->image_url) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <i class="fa-solid fa-image text-gray-300"></i>
                                @endif
                            </div>
                            <div class="text-sm">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $destination->name }}</div>
                                <div class="text-xs text-gray-500">{{ $destination->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $destination->country?->name ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $destination->university_count }}</td>
                    <td class="px-6 py-4 text-sm">{{ $destination->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.destinations.edit', $destination) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.destinations.destroy', $destination) }}" method="POST" onsubmit="return confirm('Delete this destination?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No destinations found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $destinations->links() }}</div>
</div>
@endsection
