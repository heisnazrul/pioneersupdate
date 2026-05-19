@extends('layouts.admin')

@section('title', 'Edit University Application')
@section('header', 'Edit University Application')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.uni-applications.update', $uniApplication) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Name *</label>
                    <input type="text" name="name" value="{{ old('name', $uniApplication->name) }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $uniApplication->email) }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $uniApplication->phone) }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Intake *</label>
                    <input type="text" name="intake" value="{{ old('intake', $uniApplication->intake) }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status *</label>
                <input type="text" name="status" value="{{ old('status', $uniApplication->status) }}" required class="w-full px-4 py-2 border rounded-lg bg-white dark:bg-gray-700">
            </div>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-medium">Update Application</button>
            <a href="{{ route('admin.uni-applications.index') }}" class="bg-gray-100 dark:bg-gray-700 px-6 py-3 rounded-lg font-medium">Cancel</a>
        </div>
    </form>
</div>
@endsection
