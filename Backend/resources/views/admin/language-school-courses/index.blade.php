@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Language Courses</h2>
    <a href="{{ route('admin.language-school-courses.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add Course</a>
  </div>

  @if(session('success'))
    <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-2">
      {{ session('success') }}
    </div>
  @endif

  <div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="{{ route('admin.language-school-courses.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Country</label>
        <select name="country_id" class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">All</option>
          @foreach($countries as $country)
            <option value="{{ $country->id }}" @selected(($filters['country_id'] ?? null)==$country->id)>{{ $country->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">City</label>
        <select name="city_id" class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">All</option>
          @foreach($cities as $city)
            <option value="{{ $city->id }}" @selected(($filters['city_id'] ?? null)==$city->id)>{{ $city->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">School</label>
        <select name="school_id" class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">All</option>
          @foreach($schools as $school)
            <option value="{{ $school->id }}" @selected(($filters['school_id'] ?? null)==$school->id)>{{ $school->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Branch</label>
        <select name="branch_id" class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">All</option>
          @foreach($branches as $branch)
            <option value="{{ $branch->id }}" @selected(($filters['branch_id'] ?? null)==$branch->id)>
              {{ $branch->slug }} ({{ $branch->school->name ?? 'N/A' }})
            </option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Course Type</label>
        <select name="type_id" class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">All</option>
          @foreach($types as $type)
            <option value="{{ $type->id }}" @selected(($filters['type_id'] ?? null)==$type->id)>{{ $type->name }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Tag</label>
        <select name="tag_id" class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">All</option>
          @foreach($tags as $tag)
            <option value="{{ $tag->id }}" @selected(($filters['tag_id'] ?? null)==$tag->id)>{{ $tag->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="md:col-span-4 flex items-end gap-2">
        <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Filter</button>
        <a href="{{ route('admin.language-school-courses.index') }}" class="ti-btn rounded-full border">Reset</a>
      </div>
    </form>
  </div>

  <div class="overflow-x-auto bg-white rounded-lg shadow-md">
    <table class="min-w-full bg-white">
      <thead>
        <tr class="border-b">
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Course</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Branch</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Type</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Tag</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Slug</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($courses as $course)
        <tr class="border-b">
          <td class="px-6 py-4 text-sm">{{ $course->name }}</td>
          <td class="px-6 py-4 text-sm">{{ optional($course->branch->school)->name }} / {{ $course->branch->slug ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ $course->type->name ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ $course->tag->name ?? '—' }}</td>
          <td class="px-6 py-4 text-sm font-mono">{{ $course->slug }}</td>
          <td class="px-6 py-4 text-sm">
            <a href="{{ route('admin.language-school-courses.edit', $course) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
            <form action="{{ route('admin.language-school-courses.destroy', $course) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this course?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-6 py-4 text-sm text-gray-500">No language courses found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="px-6 py-4">
      {{ $courses->links() }}
    </div>
  </div>
</div>
@endsection
