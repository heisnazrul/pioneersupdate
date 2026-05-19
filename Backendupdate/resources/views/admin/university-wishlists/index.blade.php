@extends('layouts.admin')

@section('title', 'University Wishlists')
@section('header', 'University Wishlists')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">User</th>
                <th class="px-6 py-4">Course</th>
                <th class="px-6 py-4">University</th>
                <th class="px-6 py-4">Added</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($wishlists as $wishlist)
                <tr>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $wishlist->user?->name }}</div>
                        <div class="text-xs text-gray-500">{{ $wishlist->user?->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $wishlist->course?->name ?? 'Course removed' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $wishlist->course?->university?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $wishlist->created_at?->format('Y-m-d') }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end">
                            <form action="{{ route('admin.university-wishlists.destroy', $wishlist) }}" method="POST" onsubmit="return confirm('Delete this wishlist entry?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No wishlist entries found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $wishlists->links() }}</div>
</div>
@endsection
