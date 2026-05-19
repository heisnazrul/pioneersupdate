@extends('layouts.admin')
@section('title', 'Edit Bedroom Type')
@section('header', 'Edit Type')
@section('content')
<div class="max-w-2xl"><div class="mb-6"><a href="{{ route('admin.bedroom-types.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors"><i class="fa-solid fa-arrow-left"></i> Back to Types</a></div>
<form action="{{ route('admin.bedroom-types.update', $bedroomType) }}" method="POST" class="space-y-6">@csrf @method('PUT')
<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-4">
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name (English) *</label><input type="text" name="name_en" value="{{ old('name_en', $bedroomType->name_en) }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></div>
<div><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name (Arabic)</label><input type="text" name="name_ar" value="{{ old('name_ar', $bedroomType->name_ar) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" dir="rtl"></div>
</div><div class="flex justify-end gap-3"><a href="{{ route('admin.bedroom-types.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-colors">Cancel</a><button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-2 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">Update Type</button></div></form></div>
@endsection
