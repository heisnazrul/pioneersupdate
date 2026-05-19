@extends('layouts.admin')

@section('title', 'Destination Guides')
@section('header', 'Destination Guides')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Destination Guides</h3>
    <a href="{{ route('admin.destination-guides.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Add Guide</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Title</th>
                <th class="px-6 py-4">Destination</th>
                <th class="px-6 py-4">Year</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($guides as $guide)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $guide->title }}</td>
                    <td class="px-6 py-4 text-sm">{{ $guide->destination?->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $guide->year ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $guide->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ Storage::url($guide->file_path) }}" target="_blank" class="text-gray-600">View</a>
                            <a href="{{ route('admin.destination-guides.edit', $guide) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.destination-guides.destroy', $guide) }}" method="POST" onsubmit="return confirm('Delete this guide?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No destination guides found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $guides->links() }}</div>
</div>
@endsection
