@extends('layouts.admin')

@section('title', 'Pickup Locations')
@section('header', 'Pickup Locations')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">All Pickups</h3>
    <a href="{{ route('admin.language-school-pickups.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add Pickup
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">Location</th>
                <th class="px-6 py-4 font-semibold">School Branch</th>
                <th class="px-6 py-4 font-semibold">Fee</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($pickups as $pickup)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4">
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $pickup->pickup_location }}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $pickup->branch->school->name_en }}</span>
                        <span class="text-xs text-gray-500">{{ $pickup->branch->city->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">
                        ${{ number_format($pickup->fee, 2) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.language-school-pickups.edit', $pickup) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.language-school-pickups.destroy', $pickup) }}" method="POST" onsubmit="return confirm('Delete this pickup location?')">
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
                <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">No pickup locations found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $pickups->links() }}
    </div>
</div>
@endsection
