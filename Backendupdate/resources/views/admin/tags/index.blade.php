@extends('layouts.admin')

@section('title', 'Course Tags')
@section('header', 'Course Tags')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Course Tags</h3>
    <a href="{{ route('admin.tags.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add Tag
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">Name</th>
                <th class="px-6 py-4 font-semibold">Arabic Name</th>
                <th class="px-6 py-4 font-semibold">Slug</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($tags as $tag)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $tag->name }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400" dir="rtl">{{ $tag->ar_name }}</td>
                <td class="px-6 py-4 text-xs font-mono">{{ $tag->slug }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.tags.edit', $tag) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-500">No tags found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $tags->links() }}
    </div>
</div>
@endsection
