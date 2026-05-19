{{-- resources/views/admin/pickups/create.blade.php --}}
@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <h2 class="text-2xl font-bold mb-4">Create Pickup</h2>
        <a href="{{ route('admin.language-school-pickups.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
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

    <form action="{{ route('admin.language-school-pickups.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label for="branch_id" class="block text-sm font-medium text-gray-700">Branch</label>
            <select name="branch_id" id="branch_id"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                <option value="">-- Select Branch --</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" @selected(old('branch_id') == $branch->id)>
                        {{ optional($branch->school)->name }} — {{ $branch->slug }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="route" class="block text-sm font-medium text-gray-700">Route</label>
            <input type="text" name="route" id="route" value="{{ old('route') }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                   placeholder="Heathrow Airport">
        </div>

        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">Price (per transfer)</label>
            <input type="number" step="0.01" min="0" name="price" id="price" value="{{ old('price') }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
        </div>

        <div>
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
            <input type="text" name="notes" id="notes" value="{{ old('notes') }}"
                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
        </div>

        <div class="flex space-x-2 mt-4">
            <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Save</button>
            <a href="{{ route('admin.language-school-pickups.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">Cancel</a>
        </div>
    </form>
</div>
@endsection
