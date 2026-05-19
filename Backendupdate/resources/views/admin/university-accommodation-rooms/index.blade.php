@extends('layouts.admin')

@section('title', 'Accommodation Rooms')
@section('header', 'Accommodation Rooms')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">University Accommodation Rooms</h3>
    <a href="{{ route('admin.university-accommodation-rooms.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Add Room</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Room</th>
                <th class="px-6 py-4">Price</th>
                <th class="px-6 py-4">Slug</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($rooms as $room)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-10 rounded border bg-white overflow-hidden flex items-center justify-center">
                                @if($room->image)
                                    <img src="{{ Storage::url($room->image) }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <i class="fa-solid fa-bed text-gray-300"></i>
                                @endif
                            </div>
                            <div class="text-sm">
                                <div class="font-medium text-gray-900 dark:text-white">{{ $room->title }}</div>
                                @if($room->ar_title)
                                    <div class="text-xs text-gray-500" dir="rtl">{{ $room->ar_title }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $room->price ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $room->slug }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.university-accommodation-rooms.edit', $room) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.university-accommodation-rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Delete this room?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">No accommodation rooms found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $rooms->links() }}</div>
</div>
@endsection
