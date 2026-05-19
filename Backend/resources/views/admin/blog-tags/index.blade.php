@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex justify-between py-10">
            <div>
                <h2 class="text-2xl font-bold mb-2">Blog Tags List</h2>
                <p class="text-sm text-gray-500">Manage tags specifically for Blog posts.</p>
            </div>
            <a href="{{ route('admin.blog-tags.create') }}"
                class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
                Create Blog Tag
            </a>
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">Tag</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">Color</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                        <tr class="border-b">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="flex flex-col">
                                    <span>{{ $tag->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $tag->ar_name ?: '—' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($tag->color)
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-4 w-4 rounded-full border"
                                            style="background-color: {{ $tag->color }}"></span>
                                        <span>{{ $tag->color }}</span>
                                    </span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @php
                                    $statusClasses = $tag->is_active
                                        ? 'bg-green-100 text-green-700 border border-green-200'
                                        : 'bg-gray-100 text-gray-600 border border-gray-200';
                                @endphp
                                <span
                                    class="px-2 py-1 rounded-full text-xs {{ $statusClasses }}">{{ $tag->is_active ? 'Active' : 'Hidden' }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.blog-tags.edit', $tag) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('admin.blog-tags.destroy', $tag) }}" method="POST" class="inline-block"
                                    onsubmit="return confirm('Delete this tag?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-sm text-gray-500">No blog tags found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $tags->links() }}
            </div>
        </div>
    </div>
@endsection