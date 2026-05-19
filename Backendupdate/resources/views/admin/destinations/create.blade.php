@extends('layouts.admin')

@section('title', 'Add Destination')
@section('header', 'Add Destination')

@section('content')
<div class="max-w-7xl" x-data="destinationMediaPicker()">
    <form action="{{ route('admin.destinations.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Destination')
        @include('admin.destinations._form')
    </form>
</div>
@endsection
