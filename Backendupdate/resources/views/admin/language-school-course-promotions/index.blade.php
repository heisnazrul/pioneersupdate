@extends('layouts.admin')

@section('title', 'Course Promotions')
@section('header', 'Course Promotions')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Active Promotions</h3>
    <a href="{{ route('admin.language-school-course-promotions.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add Promotion
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">Course</th>
                <th class="px-6 py-4 font-semibold">Promotion</th>
                <th class="px-6 py-4 font-semibold">Validity</th>
                <th class="px-6 py-4 font-semibold text-center">Status</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($promotions as $promo)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $promo->course->course_name_from_school }}</span>
                        <span class="text-xs text-primary-600 font-medium">{{ $promo->course->branch->school->name_en }} ({{ $promo->course->branch->city->name }})</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-amber-100 text-amber-800">
                        {{ number_format($promo->promotion_percentage, 0) }}% OFF
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                    @if($promo->promo_from || $promo->promo_to)
                        {{ $promo->promo_from?->format('d M Y') ?: '...' }} - {{ $promo->promo_to?->format('d M Y') ?: '...' }}
                    @else
                        <span class="italic text-gray-400 text-xs">Always Active</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $promo->is_active === 'yes' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ ucfirst($promo->is_active) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.language-school-course-promotions.edit', $promo) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.language-school-course-promotions.destroy', $promo) }}" method="POST" onsubmit="return confirm('Delete this promotion?')">
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
                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">No promotions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $promotions->links() }}
    </div>
</div>
@endsection
