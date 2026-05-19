@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex justify-between py-10">
            <h2 class="text-2xl font-bold mb-4">Categories</h2>
            <a href="{{ route('admin.categories.create') }}"
                class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add Category</a>
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Name (AR)</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Slug</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Color</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Order</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr class="border-b">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $cat->name }}</td>
                            <td class="px-6 py-4 text-sm">{{ $cat->ar_name ?: '—' }}</td>
                            <td class="px-6 py-4 text-sm">{{ $cat->slug }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($cat->color)
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-3 w-3 rounded-full border"
                                            style="background-color: {{ $cat->color }}"></span>
                                        <span>{{ $cat->color }}</span>
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusClasses = $cat->is_active
                                        ? 'bg-green-100 text-green-700 border border-green-200'
                                        : 'bg-gray-100 text-gray-600 border border-gray-200';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $statusClasses }}">
                                    {{ $cat->is_active ? 'Active' : 'Hidden' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $cat->display_order }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Delete this category?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-sm text-gray-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection