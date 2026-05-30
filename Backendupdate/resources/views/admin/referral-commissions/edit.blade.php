@extends('layouts.admin')

@section('title', 'Edit Commission')
@section('header', 'Edit Commission')

@section('content')
<div class="max-w-3xl">
    @include('admin.affiliates._nav')

    <div class="mb-6">
        <a href="{{ route('admin.referral-commissions.show', $commission) }}" class="flex items-center gap-1 text-sm text-gray-500 hover:text-primary-600">
            <i class="fa-solid fa-arrow-left"></i> Back to Commission
        </a>
    </div>

    <form action="{{ route('admin.referral-commissions.update', $commission) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                    <select name="status" required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(old('status', $commission->status) === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Commission Amount *</label>
                    <input type="number" step="0.01" min="0" name="commission_amount" value="{{ old('commission_amount', $commission->commission_amount) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Commission Percent</label>
                    <input type="number" step="0.01" min="0" max="100" name="commission_percent" value="{{ old('commission_percent', $commission->commission_percent) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Given</label>
                    <input type="number" step="0.01" min="0" name="discount_given" value="{{ old('discount_given', $commission->discount_given) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payable At</label>
                    <input type="datetime-local" name="payable_at" value="{{ old('payable_at', optional($commission->payable_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paid At</label>
                    <input type="datetime-local" name="paid_at" value="{{ old('paid_at', optional($commission->paid_at)->format('Y-m-d\TH:i')) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-primary-600 px-8 py-3 font-semibold text-white hover:bg-primary-700">Save Commission</button>
        </div>
    </form>
</div>
@endsection
