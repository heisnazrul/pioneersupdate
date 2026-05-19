@extends('layouts.admin')

@section('title', 'Edit Accommodation')
@section('header', 'Edit Accommodation')

@section('content')
<div class="max-w-6xl">
    <div class="mb-6">
        <a href="{{ route('admin.language-school-accommodations.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Accommodations
        </a>
    </div>

    <form action="{{ route('admin.language-school-accommodations.update', $languageSchoolAccommodation) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Column -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">General Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Branch *</label>
                            <select name="branch_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id', $languageSchoolAccommodation->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->school->name_en }} - {{ $branch->city->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Accommodation Type *</label>
                            <select name="accommodation_type_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}" {{ old('accommodation_type_id', $languageSchoolAccommodation->accommodation_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Accommodation Name *</label>
                        <input type="text" name="name" value="{{ old('name', $languageSchoolAccommodation->name) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bedroom Type</label>
                            <select name="bedroom_type_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">None</option>
                                @foreach($bedroomTypes as $bt)
                                    <option value="{{ $bt->id }}" {{ old('bedroom_type_id', $languageSchoolAccommodation->bedroom_type_id) == $bt->id ? 'selected' : '' }}>{{ $bt->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bathroom Type</label>
                            <select name="bathroom_type_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">None</option>
                                @foreach($bathroomTypes as $bat)
                                    <option value="{{ $bat->id }}" {{ old('bathroom_type_id', $languageSchoolAccommodation->bathroom_type_id) == $bat->id ? 'selected' : '' }}>{{ $bat->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Meal Plan</label>
                            <select name="meal_plan_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">None</option>
                                @foreach($mealPlans as $mp)
                                    <option value="{{ $mp->id }}" {{ old('meal_plan_id', $languageSchoolAccommodation->meal_plan_id) == $mp->id ? 'selected' : '' }}>{{ $mp->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Supplements & Seasonal Fees</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-amber-50 dark:bg-amber-900/10 rounded-lg border border-amber-100 dark:border-amber-900/30">
                        <div class="md:col-span-3"><span class="text-xs font-bold text-amber-700 dark:text-amber-400 uppercase">Summer Supplement</span></div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Fee ($)</label>
                            <input type="number" step="0.01" name="summer_supplement_fee" value="{{ old('summer_supplement_fee', $languageSchoolAccommodation->summer_supplement_fee) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                            <input type="date" name="summer_start_date" value="{{ old('summer_start_date', $languageSchoolAccommodation->summer_start_date?->format('Y-m-d')) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                            <input type="date" name="summer_end_date" value="{{ old('summer_end_date', $languageSchoolAccommodation->summer_end_date?->format('Y-m-d')) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-100 dark:border-blue-900/30">
                        <div class="md:col-span-3"><span class="text-xs font-bold text-blue-700 dark:text-blue-400 uppercase">Winter Supplement</span></div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Fee ($)</label>
                            <input type="number" step="0.01" name="winter_supplement_fee" value="{{ old('winter_supplement_fee', $languageSchoolAccommodation->winter_supplement_fee) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                            <input type="date" name="winter_start_date" value="{{ old('winter_start_date', $languageSchoolAccommodation->winter_start_date?->format('Y-m-d')) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                            <input type="date" name="winter_end_date" value="{{ old('winter_end_date', $languageSchoolAccommodation->winter_end_date?->format('Y-m-d')) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                        <div class="md:col-span-3"><span class="text-xs font-bold text-gray-500 uppercase">Other Supplement</span></div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Supplement Name</label>
                            <input type="text" name="other_supplement_name" value="{{ old('other_supplement_name', $languageSchoolAccommodation->other_supplement_name) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Fee ($)</label>
                            <input type="number" step="0.01" name="other_supplement_fee" value="{{ old('other_supplement_fee', $languageSchoolAccommodation->other_supplement_fee) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Start Date</label>
                                <input type="date" name="other_start_date" value="{{ old('other_start_date', $languageSchoolAccommodation->other_start_date?->format('Y-m-d')) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">End Date</label>
                                <input type="date" name="other_end_date" value="{{ old('other_end_date', $languageSchoolAccommodation->other_end_date?->format('Y-m-d')) }}" class="w-full px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Fees & Deposits</h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Weekly Fee ($) *</label>
                        <input type="number" step="0.01" name="weekly_fee" value="{{ old('weekly_fee', $languageSchoolAccommodation->weekly_fee) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin Fee ($) *</label>
                        <input type="number" step="0.01" name="admin_fee" value="{{ old('admin_fee', $languageSchoolAccommodation->admin_fee) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Security Deposit ($) *</label>
                        <input type="number" step="0.01" name="security_deposit" value="{{ old('security_deposit', $languageSchoolAccommodation->security_deposit) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <hr class="border-gray-100 dark:border-gray-700">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Age</label>
                        <input type="number" name="min_age" value="{{ old('min_age', $languageSchoolAccommodation->min_age) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Under 18 Supplement ($)</label>
                        <input type="number" step="0.01" name="under_18_supplement_fee" value="{{ old('under_18_supplement_fee', $languageSchoolAccommodation->under_18_supplement_fee) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Publish Status</label>
                    <select name="is_active" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="yes" {{ old('is_active', $languageSchoolAccommodation->is_active) == 'yes' ? 'selected' : '' }}>Active</option>
                        <option value="no" {{ old('is_active', $languageSchoolAccommodation->is_active) == 'no' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-primary-500/30 transition-all text-lg">Update Accommodation</button>
                    <a href="{{ route('admin.language-school-accommodations.index') }}" class="text-center text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
