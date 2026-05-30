@extends('layouts.admin')

@section('title', 'Add Bank Account')
@section('header', 'New Bank Account')

@section('content')
<div class="max-w-3xl">
    @include('admin.finance._nav')

    <div class="mb-6">
        <a href="{{ route('admin.bank-accounts.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Bank Accounts
        </a>
    </div>

    <form action="{{ route('admin.bank-accounts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @include('admin.bank-accounts._form', ['account' => null])

        <div class="flex justify-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Save Bank Account
            </button>
        </div>
    </form>
</div>
@endsection
