@extends('layouts.admin')

@section('title', 'Edit Exchange Rate')
@section('header', 'Edit Rate')

@section('content')
<div class="max-w-2xl">
    @include('admin.finance._nav')

    <div class="mb-6">
        <a href="{{ route('admin.exchange-rates.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Rates
        </a>
    </div>

    <form action="{{ route('admin.exchange-rates.update', $rate) }}" method="POST" class="space-y-6">
        @method('PUT')
        @include('admin.exchange-rates._form', ['rate' => $rate, 'lockPair' => true])

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Update Rate
            </button>
        </div>
    </form>
</div>
@endsection
