@extends('layouts.admin')

@section('title', 'Course Wishlists')
@section('header', 'Course Wishlists')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Active Wishlists</h3>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">User</th>
                <th class="px-6 py-4 font-semibold">Course Type</th>
                <th class="px-6 py-4 font-semibold">Course ID</th>
                <th class="px-6 py-4 font-semibold">Date Added</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($wishlists as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->user->name ?? 'User #'.$item->user_id }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->course_type }}</td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">#{{ $item->course_id }}</td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-500">{{ $item->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-right">
                    <form action="{{ route('admin.wishlists.destroy', $item) }}" method="POST" onsubmit="return confirm('Remove from wishlist?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">No wishlist items found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">{{ $wishlists->links() }}</div>
</div>
@endsection
