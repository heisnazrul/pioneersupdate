@extends('layouts.admin')

@section('title', 'Add University')
@section('header', 'Add University')

@section('content')
<div class="max-w-7xl" x-data="universityMediaPicker()">
    <form action="{{ route('admin.universities.store') }}" method="POST" class="space-y-8">
        @csrf
        @php($submitLabel = 'Save University')
        @include('admin.universities._form')
    </form>
</div>
@endsection
