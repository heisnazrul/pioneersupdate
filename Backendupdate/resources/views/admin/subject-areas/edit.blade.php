@extends('layouts.admin')

@section('title', 'Edit Subject Area')
@section('header', 'Edit Subject Area')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.subject-areas.update', $subjectArea) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Subject Area')
        @include('admin.subject-areas._form')
    </form>
</div>
@endsection
