@extends('admin.layouts.layout')

@section('title', 'Edit Accommodation Room')

@section('content')
    <div class="main-content py-10">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Edit Room: {{ $accommodationRoom->title }}</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Update the details for this accommodation option.</p>
        </div>

        <form action="{{ route('admin.accommodation-rooms.update', $accommodationRoom->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.accommodation_rooms._form')
        </form>
    </div>
@endsection