@extends('layouts.admin')

@section('title', 'Edit Level')
@section('header', 'Edit Level')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.levels.update', $level) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Level')
        @include('admin.levels._form')
    </form>
</div>
@endsection
