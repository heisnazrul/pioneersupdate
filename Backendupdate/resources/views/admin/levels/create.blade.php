@extends('layouts.admin')

@section('title', 'Add Level')
@section('header', 'Add Level')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.levels.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Level')
        @include('admin.levels._form')
    </form>
</div>
@endsection
