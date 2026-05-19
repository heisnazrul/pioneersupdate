@extends('layouts.admin')

@section('title', 'Edit University')
@section('header', 'Edit University')

@section('content')
<div class="max-w-7xl" x-data="universityMediaPicker()">
    <form action="{{ route('admin.universities.update', $university) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update University')
        @include('admin.universities._form')
    </form>
</div>
@endsection
