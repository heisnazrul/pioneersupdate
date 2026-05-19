@extends('layouts.admin')
@section('title', 'Meal Plans')
@section('header', 'Meal Plans')
@section('content')
<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Plans</h3>
    <a href="{{ route('admin.meal-plans.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2"><i class="fa-solid fa-plus"></i> Add Plan</a>
</div>
<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead><tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider"><th class="px-6 py-4 font-semibold">Name (EN)</th><th class="px-6 py-4 font-semibold">Name (AR)</th><th class="px-6 py-4 font-semibold text-right">Actions</th></tr></thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($plans as $plan)<tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors"><td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $plan->name_en }}</td><td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400" dir="rtl">{{ $plan->name_ar }}</td><td class="px-6 py-4 text-right"><div class="flex justify-end gap-2"><a href="{{ route('admin.meal-plans.edit', $plan) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1"><i class="fa-solid fa-pen-to-square"></i></a><form action="{{ route('admin.meal-plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan?')">@csrf @method('DELETE')<button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1"><i class="fa-solid fa-trash-can"></i></button></form></div></td></tr>@empty<tr><td colspan="3" class="px-6 py-10 text-center text-gray-500 italic">No plans found.</td></tr>@endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">{{ $plans->links() }}</div>
</div>
@endsection
