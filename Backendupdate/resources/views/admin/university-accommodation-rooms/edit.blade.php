@extends('layouts.admin')

@section('title', 'Edit Accommodation Room')
@section('header', 'Edit Accommodation Room')

@section('content')
<div class="max-w-6xl" x-data="accommodationRoomMediaPicker()">
    <form action="{{ route('admin.university-accommodation-rooms.update', $universityAccommodationRoom) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Room')
        @include('admin.university-accommodation-rooms._form')
    </form>
</div>
@endsection
