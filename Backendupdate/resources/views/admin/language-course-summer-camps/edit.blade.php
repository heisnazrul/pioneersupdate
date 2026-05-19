@extends('layouts.admin')

@section('title', 'Edit Summer Camp')
@section('header', 'Edit Summer Camp')

@section('content')
<div class="max-w-7xl" x-data="summerCampForm()">
    <div class="mb-6">
        <a href="{{ route('admin.language-course-summer-camps.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Summer Camps
        </a>
    </div>

    <form action="{{ route('admin.language-course-summer-camps.update', $languageCourseSummerCamp) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        @php($camp = $languageCourseSummerCamp)
        @php($detail = $languageCourseSummerCamp->detail)
        @php($submitLabel = 'Update Summer Camp')
        @include('admin.language-course-summer-camps._form')
    </form>
</div>
@endsection
