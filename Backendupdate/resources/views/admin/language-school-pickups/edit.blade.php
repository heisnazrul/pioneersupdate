@extends('layouts.admin')

@section('title', 'Edit Pickup Location')
@section('header', 'Edit Pickup')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.language-school-pickups.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Pickups
        </a>
    </div>

    <form action="{{ route('admin.language-school-pickups.update', $languageSchoolPickup) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Branch *</label>
                <select name="branch_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $languageSchoolPickup->branch_id) == $branch->id ? 'selected' : '' }}>
                            {{ $branch->school->name_en }} ({{ $branch->city->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pickup Location Name *</label>
                <input type="text" name="pickup_location" value="{{ old('pickup_location', $languageSchoolPickup->pickup_location) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pickup Fee ($) *</label>
                <input type="number" step="0.01" name="fee" value="{{ old('fee', $languageSchoolPickup->fee) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.language-school-pickups.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-2 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Update Pickup Location
            </button>
        </div>
    </form>
</div>
@endsection
