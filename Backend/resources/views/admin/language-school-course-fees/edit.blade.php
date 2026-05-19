@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Edit Language Course Fee</h2>
    <a href="{{ route('admin.language-school-course-fees.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
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

  <form action="{{ route('admin.language-school-course-fees.update', $fee) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700">Language Course</label>
      <select name="language_school_course_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
        @foreach($courses as $course)
          <option value="{{ $course->id }}" @selected(old('language_school_course_id', $fee->language_school_course_id) == $course->id)>
            {{ $course->name }} - {{ optional($course->branch->school)->name ?? 'N/A' }} - {{ optional($course->branch->city)->name ?? 'N/A' }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Week Number</label>
        <input type="number" name="week_number" min="1" value="{{ old('week_number', $fee->week_number) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Fee</label>
        <input type="number" step="0.01" min="0" name="fee" value="{{ old('fee', $fee->fee) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Price Split</label>
        <select name="price_split" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          <option value="yes" @selected(old('price_split', $fee->price_split) === 'yes')>Yes</option>
          <option value="no" @selected(old('price_split', $fee->price_split) === 'no')>No</option>
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Valid From</label>
        <input type="date" name="valid_from" value="{{ old('valid_from', optional($fee->valid_from)->format('Y-m-d')) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Valid To</label>
        <input type="date" name="valid_to" value="{{ old('valid_to', optional($fee->valid_to)->format('Y-m-d')) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Update</button>
      <a href="{{ route('admin.language-school-course-fees.index') }}" class="ti-btn rounded-full border">Cancel</a>
    </div>
  </form>
</div>
@endsection
