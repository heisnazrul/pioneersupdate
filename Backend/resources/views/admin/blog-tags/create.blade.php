@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex justify-between py-10">
            <h2 class="text-2xl font-bold mb-4">Create Blog Tag</h2>
            <a href="{{ route('admin.blog-tags.index') }}"
                class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
                Back to Application
            </a>
        </div>

        <form action="{{ route('admin.blog-tags.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="name" class="block text-sm font-medium text-gray-700">Tag Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>
                <div class="form-group">
                    <label for="ar_name" class="block text-sm font-medium text-gray-700">Arabic Name</label>
                    <input type="text" name="ar_name" id="ar_name" value="{{ old('ar_name') }}"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="form-group">
                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                    <div class="flex items-center gap-3 mt-1">
                        <input type="color" name="color" id="color" value="{{ old('color', '#6366f1') }}"
                            class="h-10 w-16 border rounded">
                        <span class="text-xs text-gray-500">Optional badge color.</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full border rounded-md px-3 py-2">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="ar_description" class="block text-sm font-medium text-gray-700">Arabic Description</label>
                    <textarea name="ar_description" id="ar_description" rows="3"
                        class="mt-1 block w-full border rounded-md px-3 py-2">{{ old('ar_description') }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="display_order" class="block text-sm font-medium text-gray-700">Display Order</label>
                    <input type="number" name="display_order" id="display_order" value="{{ old('display_order', 0) }}"
                        min="0"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div class="form-group flex items-center gap-3 mt-6">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                        class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('is_active', '1') === '1' ? 'checked' : '' }}>
                    <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
                </div>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
                    Save Tag
                </button>
                <a href="{{ route('admin.blog-tags.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection