@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex justify-between py-10">
            <div>
                <h2 class="text-2xl font-bold mb-2">Blogs</h2>
                <p class="text-sm text-gray-500">Manage published stories and drafts for every audience.</p>
            </div>
            <a href="{{ route('admin.blogs.create') }}"
                class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add Blog</a>
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
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Post</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Category</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Audience</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Publisher</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr class="border-b">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="flex items-center gap-3">
                                    @php
                                        $thumb = $blog->featured_image ? asset('storage/' . ltrim($blog->featured_image, '/')) : null;
                                    @endphp
                                    @if($thumb)
                                        <img class="w-12 h-12 rounded object-cover border" src="{{ $thumb }}"
                                            alt="{{ $blog->title }}">
                                    @endif

                                    <div>
                                        <div>{{ $blog->title }}</div>
                                        <div class="text-xs text-gray-500">{{ $blog->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $blog->category->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm capitalize">{{ $blog->audience_scope }}</td>
                            <td class="px-6 py-4 text-sm">{{ $blog->publisher->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-sm">
                                @if($blog->published_at)
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700 border border-green-200">Published
                                        {{ $blog->published_at->format('Y-m-d') }}</span>
                                @else
                                    <span
                                        class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600 border border-gray-200">Draft</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.blogs.edit', $blog) }}"
                                    class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('admin.blogs.destroy', $blog) }}" method="POST" class="inline-block ml-4"
                                    onsubmit="return confirm('Delete this blog?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-sm text-gray-500">No blogs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4">
                {{ $blogs->links() }}
            </div>
        </div>
    </div>
@endsection