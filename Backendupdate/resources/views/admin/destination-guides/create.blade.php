@extends('layouts.admin')

@section('title', 'Add Destination Guide')
@section('header', 'Add Destination Guide')

@section('content')
<div class="max-w-4xl space-y-6">
    <form action="{{ route('admin.destination-guides.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Guide')
        @include('admin.destination-guides._form')
    </form>
</div>
@endsection
