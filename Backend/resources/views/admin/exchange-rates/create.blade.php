@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Create Exchange Rate</h2>
        <a href="{{ route('admin.exchange-rates.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back</a>
    </div>
    <form action="{{ route('admin.exchange-rates.store') }}" method="POST" class="space-y-4">
        @include('admin.exchange-rates._form', ['rate' => null])
        <div class="flex gap-2">
            <button class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success" type="submit">Save</button>
            <a class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary" href="{{ route('admin.exchange-rates.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
