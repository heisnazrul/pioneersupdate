@extends('layouts.admin')

@section('title', 'Edit University Course')
@section('header', 'Edit University Course')

@section('content')
<div class="max-w-6xl space-y-6">
    <form action="{{ route('admin.university-courses.update', $universityCourse) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Course')
        @include('admin.university-courses._form')
    </form>
</div>
@endsection
