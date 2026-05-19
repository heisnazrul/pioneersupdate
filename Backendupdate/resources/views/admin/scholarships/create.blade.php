@extends('layouts.admin')

@section('title', 'Add Scholarship')
@section('header', 'Add Scholarship')

@section('content')
<div class="max-w-6xl space-y-6">
    <form action="{{ route('admin.scholarships.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Scholarship')
        @include('admin.scholarships._form')
    </form>
</div>
@endsection
