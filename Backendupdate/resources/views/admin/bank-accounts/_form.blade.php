<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Name (English) *</label>
            <input type="text" name="name_en" value="{{ old('name_en', $account->name_en ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bank Name (Arabic)</label>
            <input type="text" name="name_ar" value="{{ old('name_ar', $account->name_ar ?? '') }}" dir="rtl" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo Image</label>
            <input type="file" name="logo" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-primary-700">
            @if(!empty($account?->logo_path))
                <p class="mt-2 text-xs text-gray-400">Current: {{ $account->logo_path }}</p>
            @endif
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Logo Text (fallback)</label>
            <input type="text" name="logo_text" value="{{ old('logo_text', $account->logo_text ?? '') }}" placeholder="e.g. Al Rajhi Bank" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Beneficiary (English) *</label>
            <input type="text" name="beneficiary_en" value="{{ old('beneficiary_en', $account->beneficiary_en ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Beneficiary (Arabic)</label>
            <input type="text" name="beneficiary_ar" value="{{ old('beneficiary_ar', $account->beneficiary_ar ?? '') }}" dir="rtl" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Account Number *</label>
            <input type="text" name="account_number" value="{{ old('account_number', $account->account_number ?? '') }}" required dir="ltr" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IBAN</label>
            <input type="text" name="iban" value="{{ old('iban', $account->iban ?? '') }}" dir="ltr" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort Order</label>
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $account->sort_order ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
            <select name="is_active" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="yes" {{ old('is_active', $account->is_active ?? 'yes') === 'yes' ? 'selected' : '' }}>Active</option>
                <option value="no" {{ old('is_active', $account->is_active ?? 'yes') === 'no' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
    </div>
</div>
