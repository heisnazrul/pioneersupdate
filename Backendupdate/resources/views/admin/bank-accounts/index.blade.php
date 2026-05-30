@extends('layouts.admin')

@section('title', 'Bank Accounts')
@section('header', 'Bank Accounts')

@section('content')
<div class="max-w-6xl">
    @include('admin.finance._nav')

    <div class="mb-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">Manage bank transfer accounts shown on booking invoices and confirmations.</p>
        <a href="{{ route('admin.bank-accounts.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Add Account
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Bank</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Beneficiary</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Account</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($accounts as $account)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $account->name_en }}</div>
                            @if($account->name_ar)
                                <div class="text-xs text-gray-400" dir="rtl">{{ $account->name_ar }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $account->beneficiary_en }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300" dir="ltr">{{ $account->account_number }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $account->is_active === 'yes' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $account->is_active === 'yes' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.bank-accounts.edit', $account) }}" class="text-gray-400 hover:text-primary-600 transition-colors p-1">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('admin.bank-accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Delete this bank account?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No bank accounts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $accounts->links() }}</div>
</div>
@endsection
