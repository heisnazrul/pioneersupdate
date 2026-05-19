@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <h2 class="text-2xl font-bold mb-4">Edit Image</h2>
        <a href="{{ route('admin.gallery.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Back to Gallery
        </a>
    </div>

    <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $gallery->title) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label for="use_case" class="block text-sm font-medium text-gray-700">Use Case</label>
                <input type="text" name="use_case" id="use_case" value="{{ old('use_case', $gallery->use_case) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="homepage, testimonial, etc.">
                @error('use_case')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="alt_text" class="block text-sm font-medium text-gray-700">Alt Text</label>
                <input type="text" name="alt_text" id="alt_text" value="{{ old('alt_text', $gallery->alt_text) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('alt_text')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="block text-sm font-medium text-gray-700">Current Image</label>
                <div class="mt-1">
                    <img src="{{ asset('storage/'.$gallery->image_path) }}" alt="{{ $gallery->alt_text ?? $gallery->title ?? 'Gallery image' }}" class="w-40 h-32 object-cover rounded border">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="image" class="block text-sm font-medium text-gray-700">Replace Image</label>
            <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-700">
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex space-x-2">
            <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
                Update
            </button>
            <a href="{{ route('admin.gallery.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
