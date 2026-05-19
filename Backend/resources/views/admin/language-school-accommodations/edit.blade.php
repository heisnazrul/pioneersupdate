@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Edit Accommodation</h2>
    <a href="{{ route('admin.language-school-accommodations.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
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

  <form action="{{ route('admin.language-school-accommodations.update', $accommodation) }}" method="POST" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Title (EN)</label>
        <input type="text" name="title" value="{{ old('title', $accommodation->title) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Title (AR)</label>
        <input type="text" name="ar_title" value="{{ old('ar_title', $accommodation->ar_title) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Branch</label>
        <select name="branch_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          @foreach($branches as $branch)
            <option value="{{ $branch->id }}" @selected(old('branch_id', $accommodation->branch_id) == $branch->id)>
              {{ optional($branch->school)->name }} — {{ $branch->slug }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Language Course Tag</label>
        <select name="language_course_tag_id" class="mt-1 block w-full border rounded-md px-3 py-2">
          <option value="">-- None --</option>
          @foreach($tags as $tag)
            <option value="{{ $tag->id }}" @selected(old('language_course_tag_id', $accommodation->language_course_tag_id) == $tag->id)>{{ $tag->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Required Age</label>
        <input type="number" min="0" max="100" name="required_age" value="{{ old('required_age', $accommodation->required_age) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Fee per Week</label>
        <input type="number" step="0.01" min="0" name="fee_per_week" value="{{ old('fee_per_week', $accommodation->fee_per_week) }}" class="mt-1 block w-full border rounded-md px-3 py-2" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Admin Charge</label>
        <input type="number" step="0.01" min="0" name="admin_charge" value="{{ old('admin_charge', $accommodation->admin_charge) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Under 18 Supplement / Week</label>
        <input type="number" step="0.01" min="0" name="under18_supplement_per_week" value="{{ old('under18_supplement_per_week', $accommodation->under18_supplement_per_week) }}" class="mt-1 block w-full border rounded-md px-3 py-2">
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Bedroom Type</label>
        <select name="bedroom_type_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          @foreach($bedroomTypes as $type)
            <option value="{{ $type->id }}" @selected(old('bedroom_type_id', $accommodation->bedroom_type_id) == $type->id)>{{ $type->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Bathroom Type</label>
        <select name="bathroom_type_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          @foreach($bathroomTypes as $type)
            <option value="{{ $type->id }}" @selected(old('bathroom_type_id', $accommodation->bathroom_type_id) == $type->id)>{{ $type->name }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Meal Plan</label>
        <select name="meal_plan_id" class="mt-1 block w-full border rounded-md px-3 py-2" required>
          @foreach($mealPlans as $plan)
            <option value="{{ $plan->id }}" @selected(old('meal_plan_id', $accommodation->meal_plan_id) == $plan->id)>{{ $plan->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="md:col-span-3">
        <label class="block text-sm font-medium text-gray-700">Notes</label>
        <textarea name="notes" class="mt-1 block w-full border rounded-md px-3 py-2" rows="3">{{ old('notes', $accommodation->notes) }}</textarea>
      </div>
    </div>

    <div class="flex space-x-2">
      <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Update</button>
      <a href="{{ route('admin.language-school-accommodations.index') }}" class="inline-flex items-center px-4 py-2 border rounded-full">Cancel</a>
    </div>
  </form>
</div>
@endsection
