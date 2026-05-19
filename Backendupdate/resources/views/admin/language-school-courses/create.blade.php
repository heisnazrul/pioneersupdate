@extends('layouts.admin')

@section('title', 'Add Course')
@section('header', 'New Course')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6">
        <a href="{{ route('admin.language-school-courses.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Courses
        </a>
    </div>

    <form action="{{ route('admin.language-school-courses.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Core Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">General Information</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">School Branch *</label>
                            <select name="branch_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Select Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->school->name_en }} - {{ $branch->city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Category *</label>
                            <select name="course_category_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('course_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Name from School (English) *</label>
                            <input type="text" name="course_name_from_school" value="{{ old('course_name_from_school') }}" required placeholder="e.g. Intensive English Plus" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course Name from School (Arabic)</label>
                            <input type="text" name="course_name_from_school_ar" value="{{ old('course_name_from_school_ar') }}" placeholder="اسم الدورة" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hours/Week</label>
                            <input type="number" step="0.1" name="hours_per_week" value="{{ old('hours_per_week') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lessons/Week</label>
                            <input type="number" name="lessons_per_week" value="{{ old('lessons_per_week') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Level</label>
                            <input type="text" name="min_level" value="{{ old('min_level') }}" placeholder="B1, A2..." class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Age</label>
                            <input type="number" name="min_age" value="{{ old('min_age') }}" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Fees & Pricing</h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Material/Books Fee ($)</label>
                            <input type="number" step="0.01" name="material_books_fee" value="{{ old('material_books_fee', 0) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Registration/Admin Fee ($)</label>
                            <input type="number" step="0.01" name="registration_admin_fee" value="{{ old('registration_admin_fee', 0) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mandatory Add-on Fee Name</label>
                            <input type="text" name="mandatory_additional_fee_name" value="{{ old('mandatory_additional_fee_name') }}" placeholder="e.g. Summer Surcharge" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mandatory Add-on Fee ($)</label>
                            <input type="number" step="0.01" name="mandatory_additional_fee" value="{{ old('mandatory_additional_fee', 0) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Week Tiers -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6">
                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-6">Pricing Tiers (Up to 7)</h4>
                    
                    <div class="space-y-4">
                        @for($i = 1; $i <= 7; $i++)
                        <div class="flex items-center gap-3 p-3 border border-gray-100 dark:border-gray-700 rounded-lg">
                            <span class="text-xs font-bold text-gray-400 w-4">{{ $i }}</span>
                            <div class="flex-1">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">From Weeks</label>
                                <input type="number" name="week_category_{{ $i }}" value="{{ old('week_category_'.$i) }}" placeholder="1" class="w-full text-sm border-0 border-b border-gray-200 dark:border-gray-700 bg-transparent px-0 py-1 focus:ring-0 focus:border-primary-500">
                            </div>
                            <div class="flex-1">
                                <label class="block text-[10px] text-gray-400 uppercase font-bold">Fee ($/Week)</label>
                                <input type="number" step="0.01" name="weekly_fee_{{ $i }}" value="{{ old('weekly_fee_'.$i) }}" placeholder="250.00" class="w-full text-sm border-0 border-b border-gray-200 dark:border-gray-700 bg-transparent px-0 py-1 focus:ring-0 focus:border-primary-500">
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Publish Status</label>
                    <select name="is_active" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="yes">Active</option>
                        <option value="no">Inactive</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-4 rounded-xl font-bold shadow-lg shadow-primary-500/30 transition-all text-lg">
                    Save Course
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
