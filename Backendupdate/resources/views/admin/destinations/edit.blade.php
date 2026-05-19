@extends('layouts.admin')

@section('title', 'Edit Destination')
@section('header', 'Edit Destination')

@section('content')
<div class="max-w-7xl" x-data="destinationMediaPicker()">
    <form action="{{ route('admin.destinations.update', $destination) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Destination')
        @include('admin.destinations._form')
    </form>
</div>
@endsection
