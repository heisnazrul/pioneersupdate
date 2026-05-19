@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Branch High Season Fees</h2>
    <a href="{{ route('admin.language-school-branch-high-season-fees.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Add High Season Fee</a>
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
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Branch</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Season Dates</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Fee</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($fees as $fee)
        <tr class="border-b">
          <td class="px-6 py-4 text-sm">
            {{ $fee->branch->slug ?? '—' }}<br>
            <span class="text-xs text-gray-500">
              {{ optional($fee->branch->school)->name ?? 'N/A' }}
              - {{ optional($fee->branch->city)->name ?? 'N/A' }}
            </span>
          </td>
          <td class="px-6 py-4 text-sm">
            {{ optional($fee->week_start)->format('Y-m-d') ?? '—' }}
            @if(!is_null($fee->week_end))
              – {{ optional($fee->week_end)->format('Y-m-d') }}
            @endif
          </td>
          <td class="px-6 py-4 text-sm">{{ number_format((float) $fee->fee, 2) }}</td>
          <td class="px-6 py-4 text-sm">
            <a href="{{ route('admin.language-school-branch-high-season-fees.edit', $fee) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
            <form action="{{ route('admin.language-school-branch-high-season-fees.destroy', $fee) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this fee?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="px-6 py-4 text-sm text-gray-500">No high season fees found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
    <div class="px-6 py-4">
      {{ $fees->links() }}
    </div>
  </div>
</div>
@endsection
