@extends('layouts.admin')

@section('title', 'Scholarship Applications')
@section('header', 'Scholarship Applications')

@section('content')
<div class="mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search applications..." class="px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        <select name="status" class="px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            <option value="">All statuses</option>
            @foreach(['pending', 'reviewing', 'approved', 'rejected'] as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
    </form>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Application</th>
                <th class="px-6 py-4">Scholarship</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Assignee</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($applications as $application)
                <tr>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $application->application_id }}</div>
                        <div>{{ $application->first_name }} {{ $application->last_name }}</div>
                        <div class="text-xs text-gray-500">{{ $application->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $application->scholarship_title ?: '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ ucfirst($application->status) }}</td>
                    <td class="px-6 py-4 text-sm">{{ $application->assignee?->name ?: '-' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.scholarship-applications.show', $application) }}" class="text-gray-600">View</a>
                            <a href="{{ route('admin.scholarship-applications.edit', $application) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.scholarship-applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Delete this application?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No scholarship applications found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $applications->links() }}</div>
</div>
@endsection
