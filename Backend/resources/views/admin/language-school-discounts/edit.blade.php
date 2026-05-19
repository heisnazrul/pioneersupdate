@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <h2 class="text-2xl font-bold mb-4">Edit Discount</h2>
        <a href="{{ route('admin.language-school-discounts.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Back to Discounts
        </a>
    </div>

    <form action="{{ route('admin.language-school-discounts.update', ['ls_discount' => $discount->id]) }}" method="POST" class="space-y-6">
        @method('PUT')
        @include('admin.language-school-discounts._form', ['discount' => $discount, 'countries' => $countries, 'branches' => $branches])

        <div class="flex space-x-2">
            <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
                Update Discount
            </button>
            <a href="{{ route('admin.language-school-discounts.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
