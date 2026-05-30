@extends('layouts.admin')

@section('title', 'Referral Program Settings')
@section('header', 'Referral Program Settings')

@section('content')
<div class="max-w-5xl">
    @include('admin.affiliates._nav')

    <p class="mb-6 text-sm text-gray-500">Global defaults for student and agent referral programs. Agent-specific overrides can be set per agent.</p>

    <div class="grid gap-6 md:grid-cols-2">
        @foreach(['student' => 'Student Referrals', 'agent' => 'Agent Referrals'] as $scope => $label)
            @php $setting = $settings->get($scope); @endphp
            <div class="rounded-xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $label }}</h3>
                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ ($setting?->is_active ?? true) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        {{ ($setting?->is_active ?? true) ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                @if($setting)
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Friend discount</dt><dd>{{ $setting->discount_value }}{{ $setting->discount_type === 'percent' ? '%' : ' SAR' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Referrer commission</dt><dd>{{ $setting->commission_value }}{{ $setting->commission_type === 'percent' ? '%' : ' SAR' }}</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Cookie TTL</dt><dd>{{ $setting->cookie_ttl_days }} days</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Attribution window</dt><dd>{{ $setting->attribution_window_days }} days</dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Applies to</dt><dd>{{ implode(', ', $setting->applies_to ?? []) ?: 'All' }}</dd></div>
                    </dl>
                @else
                    <p class="text-sm text-gray-500">Not configured yet.</p>
                @endif
                <a href="{{ route('admin.referral-settings.edit', $scope) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700">
                    <i class="fa-solid fa-pen-to-square"></i> Edit {{ strtolower($label) }}
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
