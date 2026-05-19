@extends('layouts.admin')

@section('title', 'Edit Course Catalog')
@section('header', 'Edit Course Catalog')

@section('content')
<div class="max-w-4xl space-y-6">
    <form action="{{ route('admin.university-course-catalogs.update', $universityCourseCatalog) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Catalog')
        @include('admin.university-course-catalogs._form')
    </form>
</div>
@endsection
