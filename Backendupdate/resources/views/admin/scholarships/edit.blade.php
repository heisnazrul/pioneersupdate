@extends('layouts.admin')

@section('title', 'Edit Scholarship')
@section('header', 'Edit Scholarship')

@section('content')
<div class="max-w-6xl space-y-6">
    <form action="{{ route('admin.scholarships.update', $scholarship) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Scholarship')
        @include('admin.scholarships._form')
    </form>
</div>
@endsection
