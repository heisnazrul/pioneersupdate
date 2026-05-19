@extends('admin.layouts.layout')

@section('title', 'Accommodation Rooms')

@section('content')
<div class="main-content py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold mb-2 text-gray-800 dark:text-white">Accommodation Rooms</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Manage accommodation options available for students.</p>
        </div>
        <a href="{{ route('admin.accommodation-rooms.create') }}" class="ti-btn ti-btn-primary">
            <i class="fas fa-plus mr-2"></i> Add New Room
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr class="border-b dark:border-gray-700">
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slug</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($rooms as $room)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($room->image)
                            <img src="{{ $room->image }}" alt="{{ $room->title }}" class="h-12 w-16 object-cover rounded border dark:border-gray-600">
                        @else
                            <div class="h-12 w-16 bg-gray-100 dark:bg-gray-700 rounded border dark:border-gray-600 flex items-center justify-center text-xs text-gray-400">No Img</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $room->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $room->price }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $room->slug }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                        <a href="{{ route('admin.accommodation-rooms.edit', $room->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</a>
                        <form action="{{ route('admin.accommodation-rooms.destroy', $room->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this room?');">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 ml-2" onclick="if(confirm('Are you sure?')) { this.form.submit(); }">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                        No rooms found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection