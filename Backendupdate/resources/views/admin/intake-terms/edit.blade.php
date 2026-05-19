@extends('layouts.admin')

@section('title', 'Edit Intake Term')
@section('header', 'Edit Intake Term')

@section('content')
<div class="max-w-3xl space-y-6">
    <form action="{{ route('admin.intake-terms.update', $intakeTerm) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        @php($submitLabel = 'Update Intake Term')
        @include('admin.intake-terms._form')
    </form>
</div>
@endsection
