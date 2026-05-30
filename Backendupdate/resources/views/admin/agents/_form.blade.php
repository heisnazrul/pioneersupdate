<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl p-6 space-y-6">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $agent->user->name ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
            <input type="email" name="email" value="{{ old('email', $agent->user->email ?? '') }}" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $agent->user->phone ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">User Status *</label>
            <select name="user_status" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                @foreach(($userStatuses ?? ['active', 'inactive', 'banned']) as $status)
                    <option value="{{ $status }}" @selected(old('user_status', $agent->user->status ?? 'active') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password {{ isset($agent) ? '' : '*' }}</label>
            <input type="password" name="password" {{ isset($agent) ? '' : 'required' }} class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
    </div>

    <h3 class="text-lg font-semibold text-gray-900 dark:text-white pt-2">Agent Profile</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Company Name</label>
            <input type="text" name="company_name" value="{{ old('company_name', $agent->company_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Agent Phone</label>
            <input type="text" name="agent_phone" value="{{ old('agent_phone', $agent->phone ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Agent Status *</label>
            <select name="status" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                @foreach($statuses as $status)
                    <option value="{{ $status }}" @selected(old('status', $agent->status ?? 'pending') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referral Slug</label>
            <input type="text" name="referral_slug" value="{{ old('referral_slug', $agent->referral_slug ?? '') }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
    </div>

    <h3 class="text-lg font-semibold text-gray-900 dark:text-white pt-2">Referral & Commission</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referral Code</label>
            <input type="text" name="referral_code" value="{{ old('referral_code', $agent->referral_code ?? '') }}" placeholder="Leave blank to auto-generate" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white font-mono uppercase">
            <p class="mt-1 text-xs text-gray-400">4–20 alphanumeric characters. Must be unique across agents and students.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Referral Discount (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="referral_discount" value="{{ old('referral_discount', $agent->referral_discount ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Commission Percent (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="commission_percent" value="{{ old('commission_percent', $agent->commission_percent ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Commission Balance (SAR)</label>
            <input type="number" step="0.01" min="0" name="commission_balance" value="{{ old('commission_balance', $agent->commission_balance ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Total Commission Earned (SAR)</label>
            <input type="number" step="0.01" min="0" name="total_commission_earned" value="{{ old('total_commission_earned', $agent->total_commission_earned ?? 0) }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
    </div>
</div>
