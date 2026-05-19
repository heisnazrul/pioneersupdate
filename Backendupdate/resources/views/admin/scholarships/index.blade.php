@extends('layouts.admin')

@section('title', 'Scholarships')
@section('header', 'Scholarships')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Scholarships</h3>
    <a href="{{ route('admin.scholarships.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Add Scholarship</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Scholarship</th>
                <th class="px-6 py-4">Provider</th>
                <th class="px-6 py-4">Deadline</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($scholarships as $scholarship)
                <tr>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $scholarship->name }}</div>
                        <div class="text-xs text-gray-500">{{ $scholarship->slug }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $scholarship->university?->name ?: $scholarship->provider_name ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $scholarship->deadline_date?->format('Y-m-d') ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $scholarship->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.scholarships.edit', $scholarship) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.scholarships.destroy', $scholarship) }}" method="POST" onsubmit="return confirm('Delete this scholarship?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No scholarships found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $scholarships->links() }}</div>
</div>
@endsection
