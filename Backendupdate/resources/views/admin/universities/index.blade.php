@extends('layouts.admin')

@section('title', 'Universities')
@section('header', 'Universities')

@section('content')
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 flex-1">
        <div>
            <label class="block text-sm font-medium mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Country</label>
            <select name="country_id" class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ (string) request('country_id') === (string) $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Filter</button>
            <a href="{{ route('admin.universities.index') }}" class="bg-gray-100 dark:bg-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Reset</a>
        </div>
    </form>
    <a href="{{ route('admin.universities.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Add University</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 dark:bg-gray-900 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4">University</th>
                <th class="px-6 py-4">Location</th>
                <th class="px-6 py-4">Rankings</th>
                <th class="px-6 py-4">State</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($universities as $university)
                <tr>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded border bg-white flex items-center justify-center overflow-hidden">
                                @if($university->logo)
                                    <img src="{{ Storage::url($university->logo) }}" class="w-full h-full object-contain" alt="">
                                @else
                                    <i class="fa-solid fa-building-columns text-gray-300"></i>
                                @endif
                            </div>
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $university->name }}</div>
                                <div class="text-xs text-gray-500">{{ $university->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div>{{ $university->city?->name }}</div>
                        <div class="text-xs text-gray-500">{{ $university->country?->name }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div>QS: {{ $university->qs_ranking ?: '-' }}</div>
                        <div>THE: {{ $university->the_ranking ?: '-' }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <div>{{ $university->is_active ? 'Active' : 'Inactive' }}</div>
                        <div class="text-xs text-gray-500">{{ $university->is_featured ? 'Featured' : 'Standard' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.universities.edit', $university) }}" class="text-primary-600">Edit</a>
                            <form action="{{ route('admin.universities.destroy', $university) }}" method="POST" onsubmit="return confirm('Delete this university?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">No universities found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $universities->links() }}</div>
</div>
@endsection
