<div class="mb-6 rounded-xl border border-gray-200 bg-white p-2 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.exchange-rates.index') }}"
           class="{{ request()->routeIs('admin.exchange-rates.*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            <i class="fa-solid fa-money-bill-transfer"></i>
            <span>Exchange Rates</span>
        </a>
        <a href="{{ route('admin.conversion-fees.index') }}"
           class="{{ request()->routeIs('admin.conversion-fees.*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            <i class="fa-solid fa-percent"></i>
            <span>Conversion Fees</span>
        </a>
    </div>
</div>
