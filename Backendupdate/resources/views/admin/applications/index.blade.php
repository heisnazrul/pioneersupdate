@extends('layouts.admin')

@section('title', 'Applications Management')
@section('header', 'Applications')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2">
        <a href="{{ route('admin.applications.index') }}" class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }} text-sm font-medium transition-colors">
            All
        </a>
        @foreach($statuses as $status)
            <a href="{{ route('admin.applications.index', ['status' => $status]) }}" class="px-4 py-2 rounded-lg {{ request('status') == $status ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }} text-sm font-medium transition-colors">
                {{ ucfirst($status) }}
            </a>
        @endforeach
    </div>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">Applicant</th>
                <th class="px-6 py-4 font-semibold">ID</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold">Assigned To</th>
                <th class="px-6 py-4 font-semibold">Date</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($applications as $app)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $app->first_name }} {{ $app->last_name }}</p>
                    <p class="text-xs text-gray-500">{{ $app->email }}</p>
                </td>
                <td class="px-6 py-4 font-mono text-xs">{{ $app->application_id }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        {{ $app->status == 'approved' ? 'bg-green-100 text-green-800' : 
                          ($app->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                          ($app->status == 'reviewing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                        {{ ucfirst($app->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-xs">
                    {{ $app->assignedUser ? $app->assignedUser->name : 'Unassigned' }}
                </td>
                <td class="px-6 py-4 text-xs text-gray-500">
                    {{ $app->created_at->format('M d, Y') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.applications.show', $app) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="{{ route('admin.applications.edit', $app) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-500">No applications found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $applications->links() }}
    </div>
</div>
@endsection
