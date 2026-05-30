<div class="mb-6 rounded-xl border border-gray-200 bg-white p-2 shadow-sm dark:border-gray-700 dark:bg-gray-800">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.agents.index') }}"
           class="{{ request()->routeIs('admin.agents.*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            <i class="fa-solid fa-user-tie"></i>
            <span>Agents</span>
        </a>
        <a href="{{ route('admin.referral-settings.index') }}"
           class="{{ request()->routeIs('admin.referral-settings.*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            <i class="fa-solid fa-sliders"></i>
            <span>Program Settings</span>
        </a>
        <a href="{{ route('admin.referral-commissions.index') }}"
           class="{{ request()->routeIs('admin.referral-commissions.*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            <i class="fa-solid fa-hand-holding-dollar"></i>
            <span>Commissions</span>
        </a>
        <a href="{{ route('admin.referral-attributions.index') }}"
           class="{{ request()->routeIs('admin.referral-attributions.*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            <i class="fa-solid fa-link"></i>
            <span>Attributions</span>
        </a>
        <a href="{{ route('admin.payout-requests.index') }}"
           class="{{ request()->routeIs('admin.payout-requests.*') ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700' }} inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            <i class="fa-solid fa-money-check-dollar"></i>
            <span>Payouts</span>
        </a>
    </div>
</div>
