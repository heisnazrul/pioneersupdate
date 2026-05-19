@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <div>
            <h2 class="text-2xl font-bold mb-2">Gallery</h2>
            <p class="text-sm text-gray-500">Manage uploaded images by use case.</p>
        </div>
        <a href="{{ route('admin.gallery.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Upload Image
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-2">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($items as $item)
            <div class="bg-white rounded-lg shadow p-4 flex flex-col">
                <div class="aspect-w-4 aspect-h-3 mb-3">
                    <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->alt_text ?? $item->title ?? 'Gallery image' }}" class="w-full h-full object-cover rounded">
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-sm font-semibold text-gray-900">{{ $item->title ?: 'Untitled' }}</span>
                    <span class="text-xs text-gray-500">Use case: {{ $item->use_case ?: '—' }}</span>
                    <span class="text-xs text-gray-500">Alt: {{ $item->alt_text ?: '—' }}</span>
                </div>
                <div class="mt-3 flex justify-between text-sm">
                    <a href="{{ route('admin.gallery.edit', $item) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                    <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this image?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full text-sm text-gray-500">No images uploaded yet.</div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
@endsection
