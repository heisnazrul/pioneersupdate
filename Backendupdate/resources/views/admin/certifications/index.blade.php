@extends('layouts.admin')

@section('title', 'Certifications')
@section('header', 'Certifications')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All Certifications</h3>
    <a href="{{ route('admin.certifications.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add Certification
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">Image</th>
                <th class="px-6 py-4 font-semibold">Title (EN)</th>
                <th class="px-6 py-4 font-semibold">Subtitle (EN)</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($certifications as $item)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4">
                    @if($item->image)
                        <img src="{{ Storage::url($item->image) }}" class="h-10 w-10 object-contain rounded border border-gray-100 dark:border-gray-700 p-1 bg-white">
                    @else
                        <div class="h-10 w-10 flex items-center justify-center bg-gray-50 dark:bg-gray-900 rounded border border-dashed border-gray-200 dark:border-gray-700">
                            <i class="fa-solid fa-image text-gray-300"></i>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                    <div>{{ $item->title_en }}</div>
                    <div class="text-[10px] text-gray-400" dir="rtl">{{ $item->title_ar }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                    <div>{{ $item->subtitle_en }}</div>
                    <div class="text-[10px] text-gray-400" dir="rtl">{{ $item->subtitle_ar }}</div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.certifications.edit', $item) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.certifications.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this certification?')">
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
            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No certifications found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">{{ $certifications->links() }}</div>
</div>
@endsection
