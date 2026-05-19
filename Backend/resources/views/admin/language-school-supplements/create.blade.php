@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Create Supplement</h2>
    <a href="{{ route('admin.language-school-supplements.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
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

  <form action="{{ route('admin.language-school-supplements.store') }}" method="POST" class="space-y-4">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Name (AR)</label>
        <input type="text" name="ar_name" value="{{ old('ar_name') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Branch</label>
        <select name="branch_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          <option value="">-- Select Branch --</option>
          @foreach($branches as $branch)
            <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>
              {{ optional($branch->school)->name }} — {{ $branch->slug }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Fee</label>
        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Start Date (optional)</label>
        <input type="date" name="start_date" value="{{ old('start_date') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">End Date (optional)</label>
        <input type="date" name="end_date" value="{{ old('end_date') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Save</button>
      <a href="{{ route('admin.language-school-supplements.index') }}" class="inline-flex items-center px-4 py-2 border rounded-full">Cancel</a>
    </div>
  </form>
</div>
@endsection
