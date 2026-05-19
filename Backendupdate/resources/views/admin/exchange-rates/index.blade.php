@extends('layouts.admin')

@section('title', 'Exchange Rates Management')
@section('header', 'Exchange Rates')

@section('content')
@include('admin.finance._nav')

<div class="mb-6 flex justify-between items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Currency Exchange Rates</h3>
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.exchange-rates.refresh-gbp') }}" method="POST">
            @csrf
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                <i class="fa-solid fa-rotate"></i> Refresh GBP Rates
            </button>
        </form>
        <a href="{{ route('admin.exchange-rates.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add Rate
        </a>
    </div>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">Base</th>
                <th class="px-6 py-4 font-semibold">Target</th>
                <th class="px-6 py-4 font-semibold">Rate</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($rates as $rate)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $rate->base_currency }}</td>
                <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">{{ $rate->target_currency }}</td>
                <td class="px-6 py-4 font-mono text-sm text-primary-600">{{ number_format($rate->rate, 4) }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.exchange-rates.edit', $rate) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <form action="{{ route('admin.exchange-rates.destroy', $rate) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-500">No rates found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $rates->links() }}
    </div>
</div>
@endsection
