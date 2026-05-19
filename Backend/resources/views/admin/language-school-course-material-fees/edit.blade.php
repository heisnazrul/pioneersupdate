@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Edit Material Fee</h2>
    <a href="{{ route('admin.language-school-course-material-fees.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
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

  <form action="{{ route('admin.language-school-course-material-fees.update', $fee) }}" method="POST" class="space-y-4">
    @csrf
    @method('PUT')

    <div>
      <label class="block text-sm font-medium text-gray-700">Language Course</label>
      <select name="language_school_course_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
        @foreach($courses as $course)
          <option value="{{ $course->id }}" @selected(old('language_school_course_id', $fee->language_school_course_id) == $course->id)>
            {{ $course->name }}
            - {{ optional($course->branch->school)->name ?? 'N/A' }}
            - {{ optional($course->branch->city)->name ?? 'N/A' }}
            @if(($course->material_fees_count ?? 0) > 0)
              - updated
            @endif
          </option>
        @endforeach
      </select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Amount</label>
        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $fee->amount) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Billing Unit</label>
        <select name="billing_unit" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          @foreach(['week','month','course'] as $unit)
            <option value="{{ $unit }}" @selected(old('billing_unit', $fee->billing_unit) == $unit)>{{ ucfirst($unit) }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Billing Count</label>
        <input type="number" min="1" name="billing_count" value="{{ old('billing_count', $fee->billing_count) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Update</button>
      <a href="{{ route('admin.language-school-course-material-fees.index') }}" class="ti-btn rounded-full border">Cancel</a>
    </div>
  </form>
</div>
@endsection
