@extends('layouts.admin')

@section('title', 'Add Intake Term')
@section('header', 'Add Intake Term')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.intake-terms.store') }}" method="POST" class="space-y-6">
        @csrf
        @php($submitLabel = 'Save Intake Term')
        @include('admin.intake-terms._form')
    </form>
</div>
@endsection
