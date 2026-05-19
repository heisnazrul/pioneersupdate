@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Edit Conversion Fee</h2>
        <a href="{{ route('admin.conversion-fees.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back</a>
    </div>
    <form action="{{ route('admin.conversion-fees.update', $fee) }}" method="POST" class="space-y-4">
        @method('PUT')
        @include('admin.conversion-fees._form', ['fee' => $fee])
        <div class="flex gap-2">
            <button class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success" type="submit">Update</button>
            <a class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary" href="{{ route('admin.conversion-fees.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
