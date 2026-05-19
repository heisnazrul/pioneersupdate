@extends('layouts.admin')

@section('title', 'Edit Destination Guide')
@section('header', 'Edit Destination Guide')

@section('content')
<div class="max-w-4xl space-y-6">
    <form action="{{ route('admin.destination-guides.update', $destinationGuide) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Guide')
        @include('admin.destination-guides._form')
    </form>
</div>
@endsection
