@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Language Course Types</h2>
    <a href="{{ route('admin.language-course-types.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add Type</a>
  </div>

  @if(session('success'))
    <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-2">
      {{ session('success') }}
    </div>
  @endif

  <div class="overflow-x-auto bg-white rounded-lg shadow-md">
    <table class="min-w-full bg-white">
      <thead>
        <tr class="border-b">
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Code</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Name (EN)</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Name (AR)</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($types as $type)
        <tr class="border-b">
          <td class="px-6 py-4 text-sm text-gray-800">{{ $type->id }}</td>
          <td class="px-6 py-4 text-sm font-mono">{{ $type->type_code }}</td>
          <td class="px-6 py-4 text-sm">{{ $type->name }}</td>
          <td class="px-6 py-4 text-sm">{{ $type->ar_name }}</td>
          <td class="px-6 py-4 text-sm">
            <a href="{{ route('admin.language-course-types.edit', $type) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
            <form action="{{ route('admin.language-course-types.destroy', $type) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this type?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-6 py-4 text-sm text-gray-500">No language course types found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="px-6 py-4">
      {{ $types->links() }}
    </div>
  </div>
</div>
@endsection
