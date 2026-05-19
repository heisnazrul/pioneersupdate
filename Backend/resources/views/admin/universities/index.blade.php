@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex justify-between py-10">
            <h2 class="text-2xl font-bold mb-4">Universities</h2>
            <a href="{{ route('admin.universities.create') }}"
                class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add University</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-2">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search / Filter -->
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6 border">
            <form action="{{ route('admin.universities.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name..."
                    class="border rounded-md px-3 py-2">
                <select name="country_id" class="border rounded-md px-3 py-2">
                    <option value="">All Countries</option>
                    @foreach($countries as $id => $name)
                        <option value="{{ $id }}" @selected(request('country_id') == $id)>{{ $name }}</option>
                    @endforeach
                </select>
                <div class="md:col-span-2 flex items-center">
                    <button type="submit"
                        class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary mr-2">Filter</button>
                    <a href="{{ route('admin.universities.index') }}"
                        class="text-gray-500 text-sm hover:underline">Reset</a>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Uni info</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Location</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Type/Rank</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($universities as $university)
                        <tr class="border-b">
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-3">
                                    @if($university->logo)
                                        <img src="{{ asset('storage/' . $university->logo) }}"
                                            class="h-10 w-10 rounded-full object-cover border">
                                    @else
                                        <div
                                            class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400">
                                            <i class="ri-building-line"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $university->name }}</div>
                                        @if($university->website)
                                            <a href="{{ $university->website }}" target="_blank"
                                                class="text-xs text-blue-500 hover:underline">Visit Website</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="text-gray-900">{{ $university->city->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $university->country->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="capitalize">{{ $university->type }}</div>
                                <div class="text-xs text-gray-500 space-y-0.5">
                                    @if($university->qs_ranking)
                                        <div>QS Ranking: #{{ $university->qs_ranking }}</div>
                                    @endif
                                    @if($university->the_ranking)
                                        <div>THE Ranking: #{{ $university->the_ranking }}</div>
                                    @endif
                                    @if($university->shanghai_ranking)
                                        <div>Shanghai Ranking: #{{ $university->shanghai_ranking }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($university->is_featured)
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700 border border-amber-200 mr-1">Featured</span>
                                @endif
                                @php
                                    $statusClasses = $university->is_active
                                        ? 'bg-green-100 text-green-700 border border-green-200'
                                        : 'bg-gray-100 text-gray-600 border border-gray-200';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $statusClasses }}">
                                    {{ $university->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.universities.edit', $university) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('admin.universities.destroy', $university) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Delete this university?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-sm text-gray-500 text-center">No universities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $universities->links() }}
            </div>
        </div>
    </div>
@endsection
