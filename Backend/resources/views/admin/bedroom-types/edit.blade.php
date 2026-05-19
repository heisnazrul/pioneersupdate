@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Edit Bedroom Type</h2>
    <a href="{{ route('admin.bedroom-types.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
  </div>

  @if ($errors->any())
    <div class="mb-4 text-red-700 bg-red-50 border border-red-200 rounded-md px-4 py-2">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li class="text-sm">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.bedroom-types.update', $bedroomType) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700">Bedroom Code</label>
      <input type="text" name="bedroom_code" value="{{ old('bedroom_code', $bedroomType->bedroom_code) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Name (EN)</label>
      <input type="text" name="name" value="{{ old('name', $bedroomType->name) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Name (AR)</label>
      <input type="text" name="ar_name" value="{{ old('ar_name', $bedroomType->ar_name) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Description</label>
      <textarea name="description" class="mt-1 block w-full border rounded-md px-3 py-2">{{ old('description', $bedroomType->description) }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
      <textarea name="ar_description" class="mt-1 block w-full border rounded-md px-3 py-2">{{ old('ar_description', $bedroomType->ar_description) }}</textarea>
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Update</button>
      <a href="{{ route('admin.bedroom-types.index') }}" class="ti-btn rounded-full border">Cancel</a>
    </div>
  </form>
</div>
@endsection
