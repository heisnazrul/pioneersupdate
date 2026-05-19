@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
  <div class="flex justify-between py-10">
    <h2 class="text-2xl font-bold mb-4">Accommodations</h2>
    <a href="{{ route('admin.language-school-accommodations.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
      Add Accommodation
    </a>
  </div>

  @if(session('success'))
    <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded-md px-4 py-2">
      {{ session('success') }}
    </div>
  @endif

  <div class="bg-white rounded-lg shadow-md p-4 mb-6">
    <form method="GET" action="{{ route('admin.language-school-accommodations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
      <div class="md:col-span-4 flex items-end gap-2">
        <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Filter</button>
        <a href="{{ route('admin.language-school-accommodations.index') }}" class="ti-btn rounded-full border">Reset</a>
      </div>
    </form>
  </div>

  <div class="overflow-x-auto bg-white rounded-lg shadow-md">
    <table class="min-w-full bg-white">
      <thead>
        <tr class="border-b">
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Branch</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Tag</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Bedroom</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Bathroom</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Meal Plan</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Required Age</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Fee / Week</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Admin Charge</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Under 18 Supp.</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Notes</th>
          <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($accommodations as $acc)
        <tr class="border-b">
          <td class="px-6 py-4 text-sm">
            {{ optional($acc->branch->school)->name }} — {{ $acc->branch->slug ?? '—' }}
          </td>
          <td class="px-6 py-4 text-sm">{{ $acc->tag->name ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ $acc->bedroomType->name ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ $acc->bathroomType->name ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ $acc->mealPlan->name ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ $acc->required_age ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ number_format($acc->fee_per_week, 2) }}</td>
          <td class="px-6 py-4 text-sm">{{ $acc->admin_charge ? number_format($acc->admin_charge, 2) : '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ $acc->under18_supplement_per_week ? number_format($acc->under18_supplement_per_week, 2) : '—' }}</td>
          <td class="px-6 py-4 text-sm">{{ $acc->notes ?? '—' }}</td>
          <td class="px-6 py-4 text-sm">
            <a href="{{ route('admin.language-school-accommodations.edit', $acc) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
            <form action="{{ route('admin.language-school-accommodations.destroy', $acc) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this accommodation?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="12" class="px-6 py-4 text-sm text-gray-500">No accommodations found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <div class="px-6 py-4">
      {{ $accommodations->links() }}
    </div>
  </div>
</div>
@endsection
