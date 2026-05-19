@extends('layouts.admin')

@section('title', 'Summer Camps')
@section('header', 'Summer Camps')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All Summer Camps</h3>
    <a href="{{ route('admin.language-course-summer-camps.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add Summer Camp
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-semibold">Camp</th>
                    <th class="px-6 py-4 font-semibold">Branch</th>
                    <th class="px-6 py-4 font-semibold">Type</th>
                    <th class="px-6 py-4 font-semibold">Pricing</th>
                    <th class="px-6 py-4 font-semibold text-center">State</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($camps as $camp)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 shrink-0 rounded bg-white border p-1 flex items-center justify-center">
                                    @if($camp->thumbnail)
                                        <img src="{{ Storage::url($camp->thumbnail) }}" alt="" class="w-full h-full object-contain">
                                    @else
                                        <i class="fa-solid fa-campground text-gray-300"></i>
                                    @endif
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $camp->name }}</span>
                                    @if($camp->ar_name)
                                        <span class="text-xs text-gray-500">{{ $camp->ar_name }}</span>
                                    @endif
                                    <span class="text-xs text-primary-600 font-medium">{{ $camp->slug }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col text-sm">
                                <span class="text-gray-900 dark:text-white">{{ $camp->branch?->school?->name_en }}</span>
                                <span class="text-xs text-gray-500">{{ $camp->branch?->city?->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $camp->courseType?->name_en }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col text-sm">
                                <span class="font-medium text-gray-900 dark:text-white">{{ number_format((float) $camp->fee_amount, 2) }}</span>
                                <span class="text-xs text-gray-500">{{ ucfirst($camp->fee_type) }} fee</span>
                                @if(!is_null($camp->registration_fee))
                                    <span class="text-xs text-gray-500">Reg: {{ number_format((float) $camp->registration_fee, 2) }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $camp->status === 'published' ? 'bg-green-100 text-green-800' : ($camp->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ ucfirst($camp->status) }}
                                </span>
                                <span class="text-[11px] {{ $camp->visible ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $camp->visible ? 'Visible' : 'Hidden' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.language-course-summer-camps.edit', $camp) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.language-course-summer-camps.destroy', $camp) }}" method="POST" onsubmit="return confirm('Delete this summer camp?')">
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
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">No summer camps found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $camps->links() }}
    </div>
</div>
@endsection
