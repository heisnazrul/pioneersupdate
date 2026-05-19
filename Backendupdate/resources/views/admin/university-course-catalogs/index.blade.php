@extends('layouts.admin')

@section('title', 'Course Catalogs')
@section('header', 'Course Catalogs')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">University Course Catalogs</h3>
    <a href="{{ route('admin.university-course-catalogs.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Add Catalog</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Catalog</th>
                <th class="px-6 py-4">Subject Area</th>
                <th class="px-6 py-4">Levels</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($catalogs as $catalog)
                <tr>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $catalog->name }}</div>
                        <div class="text-xs text-gray-500">{{ $catalog->slug }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $catalog->subjectArea?->name ?? '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $catalog->levels->pluck('name')->join(', ') ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $catalog->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.university-course-catalogs.edit', $catalog) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.university-course-catalogs.destroy', $catalog) }}" method="POST" onsubmit="return confirm('Delete this catalog?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No course catalogs found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $catalogs->links() }}</div>
</div>
@endsection
