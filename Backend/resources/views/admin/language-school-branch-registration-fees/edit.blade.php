@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Edit Branch Registration Fee</h2>
    <a href="{{ route('admin.language-school-branch-registration-fees.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
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

  <form action="{{ route('admin.language-school-branch-registration-fees.update', $fee) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700">Branch</label>
      <select name="branch_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
        @foreach($branches as $branch)
          <option value="{{ $branch->id }}" @selected(old('branch_id', $fee->branch_id) == $branch->id)>
            {{ $branch->slug }} — {{ optional($branch->school)->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="block text-sm font-medium text-gray-700">Amount</label>
      <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $fee->amount) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Update</button>
      <a href="{{ route('admin.language-school-branch-registration-fees.index') }}" class="ti-btn rounded-full border">Cancel</a>
    </div>
  </form>
</div>
@endsection
