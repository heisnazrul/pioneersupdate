@extends('layouts.admin')

@section('title', 'Edit Online Course')
@section('header', 'Edit Online Course')

@section('content')
<div class="max-w-6xl" x-data="mediaPicker()">
    <div class="mb-6">
        <a href="{{ route('admin.language-online-courses.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Online Courses
        </a>
    </div>

    <form action="{{ route('admin.language-online-courses.update', $languageOnlineCourse) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        @php($course = $languageOnlineCourse)
        @php($submitLabel = 'Update Online Course')
        @include('admin.language-online-courses._form')
    </form>
</div>
@endsection
