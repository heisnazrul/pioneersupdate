@extends('admin.layouts.layout')

@section('title', 'Add Accommodation Room')

@section('content')
    <div class="main-content py-10">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Add New Room</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Create a new accommodation listing for students.</p>
        </div>

        <form action="{{ route('admin.accommodation-rooms.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.accommodation_rooms._form')
        </form>
    </div>
@endsection