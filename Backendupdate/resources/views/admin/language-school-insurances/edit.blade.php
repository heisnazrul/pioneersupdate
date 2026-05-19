@extends('layouts.admin')

@section('title', 'Edit Insurance Settings')
@section('header', 'Edit Insurance')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6">
        <a href="{{ route('admin.language-school-insurances.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Insurances
        </a>
    </div>

    <form action="{{ route('admin.language-school-insurances.update', $languageSchoolInsurance) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch *</label>
                <select name="branch_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $languageSchoolInsurance->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->school->name_en }} - {{ $branch->city->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weekly Fee ($)</label>
                    <input type="number" step="0.01" name="weekly_fee" value="{{ old('weekly_fee', $languageSchoolInsurance->weekly_fee) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">One-time Admin Fee ($)</label>
                    <input type="number" step="0.01" name="admin_fee" value="{{ old('admin_fee', $languageSchoolInsurance->admin_fee) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Is Mandatory? *</label>
                <select name="is_mandatory" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="no" {{ old('is_mandatory', $languageSchoolInsurance->is_mandatory) == 'no' ? 'selected' : '' }}>No (Optional for students)</option>
                    <option value="yes" {{ old('is_mandatory', $languageSchoolInsurance->is_mandatory) == 'yes' ? 'selected' : '' }}>Yes (Required for booking)</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.language-school-insurances.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-colors">Cancel</a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-2 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">Update Insurance</button>
        </div>
    </form>
</div>
@endsection
