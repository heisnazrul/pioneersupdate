@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <h2 class="text-2xl font-bold mb-4">Edit School</h2>
        <a href="{{ route('admin.language-schools.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Back to Schools
        </a>
    </div>

    @if ($errors->any())
      <div class="mb-4 text-red-700 bg-red-50 border border-red-200 rounded-md px-4 py-2">
        <ul class="list-disc pl-5 space-y-1 text-sm">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.language-schools.update', $school) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- School Name -->
        <div class="form-group">
            <label for="name" class="block text-sm font-medium text-gray-700">School Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $school->name) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>

        <div class="form-group">
            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $school->slug) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>

        <!-- Arabic Name -->
        <div class="form-group">
            <label for="ar_name" class="block text-sm font-medium text-gray-700">Arabic Name</label>
            <input type="text" name="ar_name" id="ar_name" value="{{ old('ar_name', $school->ar_name) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
        </div>

        <!-- Description -->
        <div class="form-group">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>{{ old('description', $school->description) }}</textarea>
        </div>

        <!-- Arabic Description -->
        <div class="form-group">
            <label for="ar_description" class="block text-sm font-medium text-gray-700">Arabic Description</label>
            <textarea name="ar_description" id="ar_description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>{{ old('ar_description', $school->ar_description) }}</textarea>
        </div>

        <!-- Logo -->
        <div class="form-group">
            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
            <input type="file" name="logo" id="logo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            @if($school->logo)
                <img src="{{ Storage::url($school->logo) }}" width="50" height="50" alt="Current School Logo">
            @endif
        </div>

        <!-- Accreditations -->
        <div class="form-group">
            <label for="accreditation_ids" class="block text-sm font-medium text-gray-700">Accreditations</label>
            <select name="accreditation_ids[]" id="accreditation_ids" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" multiple>
                @foreach($accreditations as $accreditation)
                    <option value="{{ $accreditation->id }}" {{ in_array($accreditation->id, old('accreditation_ids', $school->accreditation_ids ?? [])) ? 'selected' : '' }}>{{ $accreditation->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" id="rating" value="{{ old('rating', $school->rating) }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="flex items-center gap-2 mt-2">
            <input type="hidden" name="is_preferred" value="0">
            <input type="checkbox" name="is_preferred" value="1" id="is_preferred" class="rounded border-gray-300 text-primary focus:ring-primary" {{ old('is_preferred', $school->is_preferred) ? 'checked' : '' }}>
            <label for="is_preferred" class="text-sm text-gray-700">Preferred School</label>
        </div>

        <div class="flex space-x-2 mt-4">
            <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
                Update School
            </button>
            <a href="{{ route('admin.language-schools.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
