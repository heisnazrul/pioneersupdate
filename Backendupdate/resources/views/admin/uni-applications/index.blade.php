@extends('layouts.admin')

@section('title', 'University Applications')
@section('header', 'University Applications')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Applicant</th>
                <th class="px-6 py-4">Course</th>
                <th class="px-6 py-4">Intake</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($applications as $application)
                <tr>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $application->name }}</div>
                        <div class="text-xs text-gray-500">{{ $application->email }}</div>
                        <div class="text-xs text-gray-500">{{ $application->phone }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        @if($application->course)
                            <div class="font-medium text-gray-900 dark:text-white">{{ $application->course->name }}</div>
                            <div class="text-xs text-gray-500">{{ $application->course->university?->name }}</div>
                        @else
                            <span class="text-gray-500">Course removed</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $application->intake }}</td>
                    <td class="px-6 py-4 text-sm">{{ $application->status }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.uni-applications.edit', $application) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.uni-applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Delete this application?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No university applications found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $applications->links() }}</div>
</div>
@endsection
