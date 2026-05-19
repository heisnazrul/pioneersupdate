@extends('layouts.admin')

@section('title', 'Edit Training Course')
@section('header', 'Edit Training Course')

@section('content')
<div class="max-w-6xl" x-data="trainingMediaPicker()">
    <div class="mb-6">
        <a href="{{ route('admin.language-course-training-courses.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Training Courses
        </a>
    </div>

    <form action="{{ route('admin.language-course-training-courses.update', $languageCourseTrainingCourse) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        @php($course = $languageCourseTrainingCourse)
        @php($submitLabel = 'Update Training Course')
        @include('admin.language-course-training-courses._form')
    </form>
</div>
@endsection
