@extends('layouts.admin')

@section('title', 'Edit Conversion Fee')
@section('header', 'Edit Fee')

@section('content')
<div class="max-w-2xl">
    @include('admin.finance._nav')

    <div class="mb-6">
        <a href="{{ route('admin.conversion-fees.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Fees
        </a>
    </div>

    <form action="{{ route('admin.conversion-fees.update', $fee) }}" method="POST" class="space-y-6">
        @method('PUT')
        @include('admin.conversion-fees._form', ['fee' => $fee, 'lockPair' => true])

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Update Fee
            </button>
        </div>
    </form>
</div>
@endsection
