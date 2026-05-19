@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Edit School Branch</h2>
    <a href="{{ route('admin.language-school-branches.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
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

  <form action="{{ route('admin.language-school-branches.update', $branch) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700">School</label>
      <select name="language_school_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
        @foreach($schools as $school)
          <option value="{{ $school->id }}" @selected(old('language_school_id', old('school_id', $branch->language_school_id)) == $school->id)>{{ $school->name }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">City</label>
      <select name="city_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
        @foreach($cities as $city)
          <option value="{{ $city->id }}" @selected(old('city_id', $branch->city_id) == $city->id)>{{ $city->name }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Slug</label>
      <input type="text" name="slug" value="{{ old('slug', $branch->slug) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Description</label>
      <textarea name="description" class="mt-1 block w-full border rounded-md px-3 py-2">{{ old('description', $branch->description) }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
      <textarea name="ar_description" class="mt-1 block w-full border rounded-md px-3 py-2">{{ old('ar_description', $branch->ar_description) }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Gallery Images (use case: branch_image)</label>
      <select name="gallery_image_ids[]" multiple class="mt-1 block w-full border rounded-md px-3 py-2">
        @php
          $selectedImages = collect(old('gallery_image_ids', $selectedGalleryImageIds))->map(fn($v) => (int)$v)->all();
        @endphp
        @foreach($galleryImages as $image)
          <option value="{{ $image->id }}" @selected(in_array($image->id, $selectedImages, true))>
            {{ $image->title ?: ('Image '.$image->id) }} (ID: {{ $image->id }})
          </option>
        @endforeach
      </select>
      <p class="text-xs text-gray-500 mt-1">Select gallery images to attach; titles shown for selection.</p>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Gallery URLs (one per line)</label>
      <textarea name="gallery_urls" class="mt-1 block w-full border rounded-md px-3 py-2" rows="5">{{ old('gallery_urls', $galleryText) }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Video URL</label>
      <input type="text" name="video_url" value="{{ old('video_url', $branch->video_url) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Update</button>
      <a href="{{ route('admin.language-school-branches.index') }}" class="ti-btn rounded-full border">Cancel</a>
    </div>
  </form>
</div>
@endsection
