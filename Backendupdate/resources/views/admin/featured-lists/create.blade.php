@extends('layouts.admin')

@section('title', 'Add Featured List')
@section('header', 'Add Featured List')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.featured-lists.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Featured List')
        @include('admin.featured-lists._form')
    </form>
</div>
@endsection
