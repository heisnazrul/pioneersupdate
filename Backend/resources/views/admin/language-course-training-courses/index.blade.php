@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Training Courses</h2>
        <a href="{{ route('admin.language-course-training-courses.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Create</a>
    </div>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thumb</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">School / Branch</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fee</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($courses as $course)
                    <tr>
                        <td class="px-6 py-4 text-sm">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumb" class="h-10 w-14 object-cover rounded border">
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">{{ $course->name }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{ $course->school->name ?? '—' }}<br>
                            <span class="text-xs text-gray-500">{{ $course->branch->slug ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm">{{ number_format($course->fee_amount,2) }}</td>
                        <td class="px-6 py-4 text-sm">{{ ucfirst($course->status) }}</td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.language-course-training-courses.edit', $course) }}" class="text-primary hover:underline">Edit</a>
                            <form action="{{ route('admin.language-course-training-courses.destroy', $course) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-danger hover:underline" onclick="return confirm('Delete this course?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-sm text-center text-gray-500">No training courses.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $courses->links() }}</div>
</div>
@endsection
