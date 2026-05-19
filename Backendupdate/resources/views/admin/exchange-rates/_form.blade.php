@csrf

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Base Currency *</label>
            <input type="text" name="base_currency" value="{{ old('base_currency', $rate->base_currency ?? 'USD') }}" required maxlength="3" @disabled(isset($lockPair) && $lockPair) class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white uppercase disabled:bg-gray-50 dark:disabled:bg-gray-700/50 disabled:text-gray-500 dark:disabled:text-gray-300">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Target Currency *</label>
            <input type="text" name="target_currency" value="{{ old('target_currency', $rate->target_currency ?? '') }}" required maxlength="3" @disabled(isset($lockPair) && $lockPair) class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white uppercase disabled:bg-gray-50 dark:disabled:bg-gray-700/50 disabled:text-gray-500 dark:disabled:text-gray-300">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Exchange Rate *</label>
        <input type="number" step="0.00000001" min="0" name="rate" value="{{ old('rate', $rate->rate ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono text-lg">
        <p class="text-[10px] text-gray-400 mt-1">Example: 1 base currency = X target currency</p>
    </div>
</div>
