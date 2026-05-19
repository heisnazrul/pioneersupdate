@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Bathroom Types</h2>
    <a href="{{ route('admin.bathroom-types.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add Bathroom Type</a>
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
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Description</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Description (AR)</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bathroomTypes as $bathroomType)
        <tr class="border-b">
          <td class="px-6 py-4 text-sm text-gray-800">{{ $bathroomType->id }}</td>
          <td class="px-6 py-4 text-sm font-mono">{{ $bathroomType->bathroom_code }}</td>
          <td class="px-6 py-4 text-sm">{{ $bathroomType->name }}</td>
          <td class="px-6 py-4 text-sm">{{ $bathroomType->ar_name }}</td>
          <td class="px-6 py-4 text-sm">{{ $bathroomType->description }}</td>
          <td class="px-6 py-4 text-sm">{{ $bathroomType->ar_description }}</td>
          <td class="px-6 py-4 text-sm">
            <a href="{{ route('admin.bathroom-types.edit', $bathroomType) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
            <form action="{{ route('admin.bathroom-types.destroy', $bathroomType) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this bathroom type?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-6 py-4 text-sm text-gray-500">No bathroom types found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="px-6 py-4">
      {{ $bathroomTypes->links() }}
    </div>
  </div>
</div>
@endsection
