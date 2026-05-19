@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Edit Language Course</h2>
    <a href="{{ route('admin.language-school-courses.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
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

  <form action="{{ route('admin.language-school-courses.update', $course) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700">Branch</label>
      <select name="branch_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
        @foreach($branches as $branch)
          <option value="{{ $branch->id }}" @selected(old('branch_id', $course->branch_id) == $branch->id)>
            {{ optional($branch->school)->name }} / {{ $branch->slug }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Slug (optional)</label>
        <input type="text" name="slug" value="{{ old('slug', $course->slug) }}" class="mt-1 block w-full border rounded-md px-3 py-2" placeholder="Leave empty to auto-generate">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Start Day</label>
        <input type="text" name="start_day" value="{{ old('start_day', $course->start_day) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Name (EN)</label>
        <input type="text" name="name" value="{{ old('name', $course->name) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Name (AR)</label>
        <input type="text" name="ar_name" value="{{ old('ar_name', $course->ar_name) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Description</label>
      <textarea name="description" class="mt-1 block w-full border rounded-md px-3 py-2" rows="4">{{ old('description', $course->description) }}</textarea>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Description (AR)</label>
      <textarea name="ar_description" class="mt-1 block w-full border rounded-md px-3 py-2" rows="4">{{ old('ar_description', $course->ar_description) }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Course Type</label>
        <select name="language_course_type_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          @foreach($types as $type)
            <option value="{{ $type->id }}" @selected(old('language_course_type_id', $course->language_course_type_id) == $type->id)>{{ $type->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Course Tag</label>
        <select name="language_course_tag_id" class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">None</option>
          @foreach($tags as $tag)
            <option value="{{ $tag->id }}" @selected(old('language_course_tag_id', $course->language_course_tag_id) == $tag->id)>{{ $tag->name }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Required Level</label>
        <input type="text" name="required_level" value="{{ old('required_level', $course->required_level) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Study Time</label>
        <input type="text" name="study_time" value="{{ old('study_time', $course->study_time) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Lessons / Week</label>
        <input type="text" name="lessons_per_week" value="{{ old('lessons_per_week', $course->lessons_per_week) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Min Age</label>
        <input type="text" name="min_age" value="{{ old('min_age', $course->min_age) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Update</button>
      <a href="{{ route('admin.language-school-courses.index') }}" class="ti-btn rounded-full border">Cancel</a>
    </div>
  </form>
</div>
@endsection
