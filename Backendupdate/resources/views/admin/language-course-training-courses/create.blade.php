@extends('layouts.admin')

@section('title', 'Add Training Course')
@section('header', 'New Training Course')

@section('content')
<div class="max-w-6xl" x-data="trainingMediaPicker()">
    <div class="mb-6">
        <a href="{{ route('admin.language-course-training-courses.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Training Courses
        </a>
    </div>

    <form action="{{ route('admin.language-course-training-courses.store') }}" method="POST" class="space-y-8">
        @csrf
        @php($course = null)
        @php($submitLabel = 'Save Training Course')
        @include('admin.language-course-training-courses._form')
    </form>
</div>
@endsection
