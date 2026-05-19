@extends('layouts.admin')

@section('title', 'Add Summer Camp')
@section('header', 'New Summer Camp')

@section('content')
<div class="max-w-7xl" x-data="summerCampForm()">
    <div class="mb-6">
        <a href="{{ route('admin.language-course-summer-camps.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Summer Camps
        </a>
    </div>

    <form action="{{ route('admin.language-course-summer-camps.store') }}" method="POST" class="space-y-8">
        @csrf
        @php($camp = null)
        @php($detail = null)
        @php($submitLabel = 'Save Summer Camp')
        @include('admin.language-course-summer-camps._form')
    </form>
</div>
@endsection
