@extends('layouts.admin')

@section('title', 'Edit Bank Account')
@section('header', 'Edit Bank Account')

@section('content')
<div class="max-w-3xl">
    @include('admin.finance._nav')

    <div class="mb-6">
        <a href="{{ route('admin.bank-accounts.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Bank Accounts
        </a>
    </div>

    <form action="{{ route('admin.bank-accounts.update', $bankAccount) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @include('admin.bank-accounts._form', ['account' => $bankAccount])

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.bank-accounts.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-8 py-2 rounded-lg font-semibold shadow-lg shadow-primary-500/30 transition-all">
                Update Bank Account
            </button>
        </div>
    </form>
</div>
@endsection
