@extends('layouts.admin')

@section('title', 'University Courses')
@section('header', 'University Courses')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">University Courses</h3>
    <a href="{{ route('admin.university-courses.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Add Course</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">Course</th>
                <th class="px-6 py-4">University</th>
                <th class="px-6 py-4">Level</th>
                <th class="px-6 py-4">Fee</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($courses as $course)
                <tr>
                    <td class="px-6 py-4 text-sm">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $course->name }}</div>
                        <div class="text-xs text-gray-500">{{ $course->courseCatalog?->subjectArea?->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">{{ $course->university?->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $course->level?->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $course->first_year_fee ? number_format((float) $course->first_year_fee, 2) . ' ' . $course->currency : '-' }}</td>
                    <td class="px-6 py-4 text-sm">{{ $course->is_active ? 'Active' : 'Inactive' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.university-courses.edit', $course) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.university-courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this course?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">No university courses found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $courses->links() }}</div>
</div>
@endsection
