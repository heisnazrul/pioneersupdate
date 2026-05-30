@extends('layouts.admin')

@section('title', 'Edit Referral Settings')
@section('header', 'Edit ' . ucfirst($scope) . ' Referral Settings')

@section('content')
<div class="max-w-3xl">
    @include('admin.affiliates._nav')

    <div class="mb-6">
        <a href="{{ route('admin.referral-settings.index') }}" class="flex items-center gap-1 text-sm text-gray-500 hover:text-primary-600">
            <i class="fa-solid fa-arrow-left"></i> Back to Program Settings
        </a>
    </div>

    <form action="{{ route('admin.referral-settings.update', $scope) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')

        <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Type *</label>
                    <select name="discount_type" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="percent" @selected(old('discount_type', $setting->discount_type) === 'percent')>Percent</option>
                        <option value="fixed" @selected(old('discount_type', $setting->discount_type) === 'fixed')>Fixed amount</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Value *</label>
                    <input type="number" step="0.01" min="0" name="discount_value" value="{{ old('discount_value', $setting->discount_value) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Commission Type *</label>
                    <select name="commission_type" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                        <option value="percent" @selected(old('commission_type', $setting->commission_type) === 'percent')>Percent</option>
                        <option value="fixed" @selected(old('commission_type', $setting->commission_type) === 'fixed')>Fixed amount</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Commission Value *</label>
                    <input type="number" step="0.01" min="0" name="commission_value" value="{{ old('commission_value', $setting->commission_value) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Booking Amount</label>
                    <input type="number" step="0.01" min="0" name="min_booking_amount" value="{{ old('min_booking_amount', $setting->min_booking_amount) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Discount Amount</label>
                    <input type="number" step="0.01" min="0" name="max_discount_amount" value="{{ old('max_discount_amount', $setting->max_discount_amount) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Commission Amount</label>
                    <input type="number" step="0.01" min="0" name="max_commission_amount" value="{{ old('max_commission_amount', $setting->max_commission_amount) }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cookie TTL (days) *</label>
                    <input type="number" min="1" max="365" name="cookie_ttl_days" value="{{ old('cookie_ttl_days', $setting->cookie_ttl_days) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attribution Window (days) *</label>
                    <input type="number" min="1" max="730" name="attribution_window_days" value="{{ old('attribution_window_days', $setting->attribution_window_days) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Applies To</label>
                @php $selected = old('applies_to', $setting->applies_to ?? []); @endphp
                <label class="mr-4 inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="applies_to[]" value="language_courses" @checked(in_array('language_courses', $selected))> Language courses
                </label>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="applies_to[]" value="online_courses" @checked(in_array('online_courses', $selected))> Online courses
                </label>
            </div>

            <div>
                <label class="inline-flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $setting->is_active))> Program active
                </label>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-primary-600 px-8 py-3 font-semibold text-white hover:bg-primary-700">Save Settings</button>
        </div>
    </form>
</div>
@endsection
