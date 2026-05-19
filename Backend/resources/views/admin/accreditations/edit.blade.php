@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <h2 class="text-2xl font-bold mb-4">Edit Accreditation</h2>
        <a href="{{ route('admin.accreditations.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Back to Accreditations
        </a>
    </div>

    <form action="{{ route('admin.accreditations.update', $accreditation) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <!-- Accreditation Name -->
            <div class="form-group">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $accreditation->name) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>

            <!-- Arabic Name -->
            <div class="form-group">
                <label for="ar_name" class="block text-sm font-medium text-gray-700">Arabic Name</label>
                <input type="text" name="ar_name" id="ar_name" value="{{ old('ar_name', $accreditation->ar_name) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Optional">
            </div>
        </div>

        <!-- Picture -->
        <div class="form-group">
            <label for="picture" class="block text-sm font-medium text-gray-700">Picture</label>
            <input type="file" name="picture" id="picture" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" accept="image/*">
            <p class="text-xs text-gray-500 mt-1">Uploading a new file will replace the current picture.</p>
            @if($accreditation->picture)
                <div class="mt-3 flex items-center gap-3">
                    <img src="{{ asset('storage/'.ltrim($accreditation->picture, '/')) }}" width="60" height="60" alt="Current Accreditation Picture" class="rounded border">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remove_picture" value="1" class="rounded border-gray-300 text-primary focus:ring-primary">
                        Remove existing picture
                    </label>
                </div>
            @endif
        </div>

        <div class="flex space-x-2 mt-4">
            <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
                Update Accreditation
            </button>
            <a href="{{ route('admin.accreditations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
