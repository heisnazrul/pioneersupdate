@extends('layouts.admin')

@section('title', 'Add University Course')
@section('header', 'Add University Course')

@section('content')
<div class="max-w-6xl space-y-6">
    <form action="{{ route('admin.university-courses.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Course')
        @include('admin.university-courses._form')
    </form>
</div>
@endsection
