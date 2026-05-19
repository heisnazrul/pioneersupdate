@extends('layouts.admin')

@section('title', 'Add Subject Area')
@section('header', 'Add Subject Area')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.subject-areas.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Subject Area')
        @include('admin.subject-areas._form')
    </form>
</div>
@endsection
