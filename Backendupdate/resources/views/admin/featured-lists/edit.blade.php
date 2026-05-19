@extends('layouts.admin')

@section('title', 'Edit Featured List')
@section('header', 'Edit Featured List')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.featured-lists.update', $featuredList) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Featured List')
        @include('admin.featured-lists._form')
    </form>
</div>
@endsection
