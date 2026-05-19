@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <h2 class="text-2xl font-bold mb-4">Create Coupon</h2>
        <a href="{{ route('admin.language-school-coupons.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Back to Coupons
        </a>
    </div>

    <form action="{{ route('admin.language-school-coupons.store') }}" method="POST" class="space-y-6">
        @csrf
        @include('admin.language-school-coupons._form', ['coupon' => null])

        <div class="flex space-x-2">
            <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
                Save Coupon
            </button>
            <a href="{{ route('admin.language-school-coupons.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
