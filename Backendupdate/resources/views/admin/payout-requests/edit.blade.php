@extends('layouts.admin')

@section('title', 'Edit Payout')
@section('header', 'Update Payout Request')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('admin.payout-requests.update', $payout) }}" class="space-y-6 rounded-xl border border-gray-100 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
        @csrf
        @method('PUT')

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Reference</label>
            <p class="font-mono text-gray-900 dark:text-white">{{ $payout->reference_no }}</p>
            <p class="text-sm text-gray-500">{{ number_format($payout->amount, 2) }} {{ $payout->currency }} · {{ $payout->user?->name }}</p>
        </div>

        <div>
            <label for="status" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select id="status" name="status" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $payout->status) === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-gray-500">Marking as paid will deduct the amount from the user's commission balance.</p>
        </div>

        <div>
            <label for="admin_notes" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Admin notes</label>
            <textarea id="admin_notes" name="admin_notes" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-2 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('admin_notes', $payout->admin_notes) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-primary-600 px-5 py-2 text-sm text-white">Save changes</button>
            <a href="{{ route('admin.payout-requests.show', $payout) }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm text-gray-700 dark:border-gray-600 dark:text-gray-300">Cancel</a>
        </div>
    </form>
</div>
@endsection
