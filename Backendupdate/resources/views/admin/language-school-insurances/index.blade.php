@extends('layouts.admin')

@section('title', 'School Insurance')
@section('header', 'School Insurance')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Insurance Settings</h3>
    <a href="{{ route('admin.language-school-insurances.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add Settings
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">School Branch</th>
                <th class="px-6 py-4 font-semibold">Weekly Fee</th>
                <th class="px-6 py-4 font-semibold">Admin Fee</th>
                <th class="px-6 py-4 font-semibold text-center">Mandatory</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($insurances as $ins)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $ins->branch->school->name_en }}</span>
                        <span class="text-xs text-gray-500">{{ $ins->branch->city->name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm">${{ number_format($ins->weekly_fee, 2) }}</td>
                <td class="px-6 py-4 text-sm">${{ number_format($ins->admin_fee, 2) }}</td>
                <td class="px-6 py-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ins->is_mandatory === 'yes' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($ins->is_mandatory) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.language-school-insurances.edit', $ins) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.language-school-insurances.destroy', $ins) }}" method="POST" onsubmit="return confirm('Delete these settings?')">
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
            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">No insurance settings found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">{{ $insurances->links() }}</div>
</div>
@endsection
