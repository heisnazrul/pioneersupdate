@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Supplements</h2>
    <a href="{{ route('admin.language-school-supplements.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
      Add Supplement
    </a>
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
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Name</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Branch</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Start</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">End</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Fee</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($supplements as $s)
        <tr class="border-b">
          <td class="px-6 py-4 text-sm font-medium text-gray-900">
            {{ $s->name }}<br>
            <span class="text-xs text-gray-500">{{ $s->ar_name }}</span>
          </td>
          <td class="px-6 py-4 text-sm">{{ optional($s->branch->school)->name }} — {{ $s->branch->slug ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ optional($s->start_date)->format('Y-m-d') ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ optional($s->end_date)->format('Y-m-d') ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">${{ number_format($s->amount, 2) }}</td>
          <td class="px-6 py-4 text-sm">
            <a href="{{ route('admin.language-school-supplements.edit', $s) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
            <form action="{{ route('admin.language-school-supplements.destroy', $s) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this supplement?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="px-6 py-4 text-sm text-gray-500">No supplements found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="px-6 py-4">
      {{ $supplements->links() }}
    </div>
  </div>
</div>
@endsection
