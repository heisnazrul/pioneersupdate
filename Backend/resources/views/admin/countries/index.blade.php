@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex justify-between py-10">
            <h2 class="text-2xl font-bold mb-4">Countries</h2>
            <a href="{{ route('admin.countries.create') }}"
                class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add Country</a>
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Flag</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Code</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Popular</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Order</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($countries as $country)
                        <tr class="border-b">
                            <td class="px-6 py-4 text-sm">
                                @if($country->flag)
                                    <img src="{{ asset('storage/' . $country->flag) }}" alt="{{ $country->name }}"
                                        class="h-8 w-auto">
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $country->name }}
                                <div class="text-xs text-gray-500">{{ $country->ar_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $country->country_code }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($country->is_popular)
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-indigo-100 text-indigo-700 border border-indigo-200">Popular</span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusClasses = $country->is_active
                                        ? 'bg-green-100 text-green-700 border border-green-200'
                                        : 'bg-gray-100 text-gray-600 border border-gray-200';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $statusClasses }}">
                                    {{ $country->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $country->display_order }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.countries.edit', $country) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('admin.countries.destroy', $country) }}" method="POST"
                                    class="inline-block" onsubmit="return confirm('Delete this country?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-sm text-gray-500">No countries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $countries->links() }}
            </div>
        </div>
    </div>
@endsection