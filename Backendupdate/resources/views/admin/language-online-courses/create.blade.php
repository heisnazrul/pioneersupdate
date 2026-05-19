@extends('layouts.admin')

@section('title', 'Add Online Course')
@section('header', 'New Online Course')

@section('content')
<div class="max-w-6xl" x-data="mediaPicker()">
    <div class="mb-6">
        <a href="{{ route('admin.language-online-courses.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Online Courses
        </a>
    </div>

    <form action="{{ route('admin.language-online-courses.store') }}" method="POST" class="space-y-8">
        @csrf
        @php($course = null)
        @php($submitLabel = 'Save Online Course')
        @include('admin.language-online-courses._form')
    </form>
</div>
@endsection
