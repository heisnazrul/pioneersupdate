@extends('layouts.admin')

@section('title', 'Featured Lists')
@section('header', 'Featured Lists')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Featured Lists</h3>
    <a href="{{ route('admin.featured-lists.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Add Featured List</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Key</th>
                <th class="px-6 py-4">Name</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($lists as $featuredList)
                <tr>
                    <td class="px-6 py-4 text-sm">{{ $featuredList->key }}</td>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $featuredList->name }}</div>
                        @if($featuredList->ar_name)
                            <div class="text-xs text-gray-500" dir="rtl">{{ $featuredList->ar_name }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $featuredList->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.featured-lists.edit', $featuredList) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.featured-lists.destroy', $featuredList) }}" method="POST" onsubmit="return confirm('Delete this featured list?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">No featured lists found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $lists->links() }}</div>
</div>
@endsection
