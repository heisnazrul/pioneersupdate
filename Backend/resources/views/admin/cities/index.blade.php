@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex justify-between py-10">
            <h2 class="text-2xl font-bold mb-4">Cities</h2>
            <a href="{{ route('admin.cities.create') }}"
                class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add City</a>
        </div>

        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-2">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Country</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Slug</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Order</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cities as $city)
                        <tr class="border-b">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $city->name }}
                                <div class="text-xs text-gray-500">{{ $city->ar_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($city->country)
                                    <span class="inline-flex items-center gap-2">
                                        @if($city->country->flag)
                                            <img src="{{ asset('storage/' . $city->country->flag) }}" class="h-4 w-auto rounded-sm">
                                        @endif
                                        {{ $city->country->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">Unknown</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $city->slug }}</td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusClasses = $city->is_active
                                        ? 'bg-green-100 text-green-700 border border-green-200'
                                        : 'bg-gray-100 text-gray-600 border border-gray-200';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $statusClasses }}">
                                    {{ $city->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $city->display_order }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.cities.edit', $city) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Delete this city?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-sm text-gray-500">No cities found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $cities->links() }}
            </div>
        </div>
    </div>
@endsection