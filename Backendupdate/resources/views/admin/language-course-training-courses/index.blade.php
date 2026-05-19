@extends('layouts.admin')

@section('title', 'Training Courses')
@section('header', 'Training Courses')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All Training Courses</h3>
    <a href="{{ route('admin.language-course-training-courses.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add Training Course
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Course</th>
                    <th class="px-6 py-4 font-semibold">School</th>
                    <th class="px-6 py-4 font-semibold">Branch</th>
                    <th class="px-6 py-4 font-semibold">Type</th>
                    <th class="px-6 py-4 font-semibold">Pricing</th>
                    <th class="px-6 py-4 font-semibold text-center">State</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($courses as $course)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 shrink-0 rounded bg-white border p-1 flex items-center justify-center">
                                    @if($course->thumbnail)
                                        <img src="{{ Storage::url($course->thumbnail) }}" alt="" class="w-full h-full object-contain">
                                    @else
                                        <i class="fa-solid fa-briefcase text-gray-300"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $course->name }}</span>
                                    @if($course->ar_name)
                                        <span class="text-xs text-gray-500">{{ $course->ar_name }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $course->school?->name_en }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col text-sm">
                                <span class="text-gray-900 dark:text-white">{{ $course->branch?->city?->name ?? 'All Branches' }}</span>
                                @if($course->branch)
                                    <span class="text-xs text-gray-500">{{ $course->branch->school?->name_en }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $course->courseType?->name_en }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col text-sm">
                                <span class="font-medium text-gray-900 dark:text-white">{{ number_format((float) $course->fee_amount, 2) }}</span>
                                <span class="text-xs text-gray-500">{{ ucfirst($course->fee_type) }} fee</span>
                                @if(!is_null($course->registration_fee))
                                    <span class="text-xs text-gray-500">Reg: {{ number_format((float) $course->registration_fee, 2) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $course->status === 'published' ? 'bg-green-100 text-green-800' : ($course->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                                <span class="text-[11px] {{ $course->visible ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $course->visible ? 'Visible' : 'Hidden' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.language-course-training-courses.edit', $course) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.language-course-training-courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Delete this training course?')">
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
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 italic">No training courses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $courses->links() }}
    </div>
</div>
@endsection
