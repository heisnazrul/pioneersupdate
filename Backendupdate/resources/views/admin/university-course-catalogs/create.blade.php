@extends('layouts.admin')

@section('title', 'Add Course Catalog')
@section('header', 'Add Course Catalog')

@section('content')
<div class="max-w-4xl space-y-6">
    <form action="{{ route('admin.university-course-catalogs.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Catalog')
        @include('admin.university-course-catalogs._form')
    </form>
</div>
@endsection
