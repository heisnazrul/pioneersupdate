@extends('layouts.admin')

@section('title', 'Add Accommodation Room')
@section('header', 'Add Accommodation Room')

@section('content')
<div class="max-w-6xl" x-data="accommodationRoomMediaPicker()">
    <form action="{{ route('admin.university-accommodation-rooms.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Room')
        @include('admin.university-accommodation-rooms._form')
    </form>
</div>
@endsection
