@php
    $navGroups = [
        [
            'id' => 'language-schools',
            'label' => 'Language Schools',
            'icon' => 'fa-school-flag',
            'items' => [
                ['route' => 'admin.language-schools.index', 'pattern' => 'admin.language-schools.*', 'label' => 'Language Schools', 'icon' => 'fa-school-flag'],
                ['route' => 'admin.language-school-branches.index', 'pattern' => 'admin.language-school-branches.*', 'label' => 'School Branches', 'icon' => 'fa-code-branch'],
                ['route' => 'admin.accreditations.index', 'pattern' => 'admin.accreditations.*', 'label' => 'Accreditations', 'icon' => 'fa-certificate'],
                ['route' => 'admin.certifications.index', 'pattern' => 'admin.certifications.*', 'label' => 'Certifications', 'icon' => 'fa-award'],
                ['route' => 'admin.language-course-categories.index', 'pattern' => 'admin.language-course-categories.*', 'label' => 'Course Categories', 'icon' => 'fa-layer-group'],
                ['route' => 'admin.tags.index', 'pattern' => 'admin.tags.*', 'label' => 'Course Tags', 'icon' => 'fa-tag'],
                ['route' => 'admin.language-school-courses.index', 'pattern' => 'admin.language-school-courses.*', 'label' => 'School Courses', 'icon' => 'fa-book-open'],
                ['route' => 'admin.language-online-courses.index', 'pattern' => 'admin.language-online-courses.*', 'label' => 'Online Courses', 'icon' => 'fa-laptop-file'],
                ['route' => 'admin.language-course-summer-camps.index', 'pattern' => 'admin.language-course-summer-camps.*', 'label' => 'Summer Camps', 'icon' => 'fa-campground'],
                ['route' => 'admin.language-course-training-courses.index', 'pattern' => 'admin.language-course-training-courses.*', 'label' => 'Training Courses', 'icon' => 'fa-briefcase'],
                ['route' => 'admin.language-school-course-promotions.index', 'pattern' => 'admin.language-school-course-promotions.*', 'label' => 'Course Promotions', 'icon' => 'fa-tags'],
                ['route' => 'admin.language-school-pickups.index', 'pattern' => 'admin.language-school-pickups.*', 'label' => 'Airport Pickups', 'icon' => 'fa-plane-arrival'],
                ['route' => 'admin.language-school-insurances.index', 'pattern' => 'admin.language-school-insurances.*', 'label' => 'Student Insurance', 'icon' => 'fa-shield-heart'],
                ['route' => 'admin.language-school-pioneers-discounts.index', 'pattern' => 'admin.language-school-pioneers-discounts.*', 'label' => 'Pioneers Discounts', 'icon' => 'fa-percent'],
            ],
        ],
        [
            'id' => 'university',
            'label' => 'University',
            'icon' => 'fa-building-columns',
            'items' => [
                ['route' => 'admin.universities.index', 'pattern' => 'admin.universities.*', 'label' => 'Universities', 'icon' => 'fa-building-columns'],
                ['route' => 'admin.university-course-catalogs.index', 'pattern' => 'admin.university-course-catalogs.*', 'label' => 'Course Catalogs', 'icon' => 'fa-book-atlas'],
                ['route' => 'admin.university-courses.index', 'pattern' => 'admin.university-courses.*', 'label' => 'University Courses', 'icon' => 'fa-graduation-cap'],
                ['route' => 'admin.scholarships.index', 'pattern' => 'admin.scholarships.*', 'label' => 'Scholarships', 'icon' => 'fa-award'],
                ['route' => 'admin.destinations.index', 'pattern' => 'admin.destinations.*', 'label' => 'Destinations', 'icon' => 'fa-earth-americas'],
                ['route' => 'admin.destination-guides.index', 'pattern' => 'admin.destination-guides.*', 'label' => 'Destination Guides', 'icon' => 'fa-file-pdf'],
                ['route' => 'admin.university-accommodation-rooms.index', 'pattern' => 'admin.university-accommodation-rooms.*', 'label' => 'Accommodation Rooms', 'icon' => 'fa-bed'],
                ['route' => 'admin.featured-lists.index', 'pattern' => 'admin.featured-lists.*', 'label' => 'Featured Lists', 'icon' => 'fa-list-check'],
                ['route' => 'admin.levels.index', 'pattern' => 'admin.levels.*', 'label' => 'Levels', 'icon' => 'fa-layer-group'],
                ['route' => 'admin.subject-areas.index', 'pattern' => 'admin.subject-areas.*', 'label' => 'Subject Areas', 'icon' => 'fa-diagram-project'],
                ['route' => 'admin.intake-terms.index', 'pattern' => 'admin.intake-terms.*', 'label' => 'Intake Terms', 'icon' => 'fa-calendar-days'],
                ['route' => 'admin.uni-applications.index', 'pattern' => 'admin.uni-applications.*', 'label' => 'Uni Applications', 'icon' => 'fa-file-circle-check'],
                ['route' => 'admin.scholarship-applications.index', 'pattern' => 'admin.scholarship-applications.*', 'label' => 'Scholarship Applications', 'icon' => 'fa-file-invoice'],
                ['route' => 'admin.university-wishlists.index', 'pattern' => 'admin.university-wishlists.*', 'label' => 'University Wishlists', 'icon' => 'fa-heart'],
            ],
        ],
        [
            'id' => 'accommodations',
            'label' => 'Accommodations',
            'icon' => 'fa-bed',
            'items' => [
                ['route' => 'admin.language-school-accommodations.index', 'pattern' => 'admin.language-school-accommodations.*', 'label' => 'Manage Accommodations', 'icon' => 'fa-bed'],
                ['route' => 'admin.accommodation-types.index', 'pattern' => 'admin.accommodation-types.*', 'label' => 'Accommodation Types', 'icon' => 'fa-house-user'],
                ['route' => 'admin.meal-plans.index', 'pattern' => 'admin.meal-plans.*', 'label' => 'Meal Plans', 'icon' => 'fa-utensils'],
                ['route' => 'admin.bedroom-types.index', 'pattern' => 'admin.bedroom-types.*', 'label' => 'Bedroom Types', 'icon' => 'fa-door-open'],
                ['route' => 'admin.bathroom-types.index', 'pattern' => 'admin.bathroom-types.*', 'label' => 'Bathroom Types', 'icon' => 'fa-toilet'],
            ],
        ],
        [
            'id' => 'content',
            'label' => 'Content',
            'icon' => 'fa-newspaper',
            'items' => [
                ['route' => 'admin.blogs.index', 'pattern' => ['admin.blogs.*', 'admin.categories.*', 'admin.blog-tags.*'], 'label' => 'Blog & News', 'icon' => 'fa-newspaper'],
                ['route' => 'admin.reviews.index', 'pattern' => 'admin.reviews.*', 'label' => 'Reviews', 'icon' => 'fa-star'],
                ['route' => 'admin.contact-submissions.index', 'pattern' => 'admin.contact-submissions.*', 'label' => 'Inquiries', 'icon' => 'fa-envelope'],
                ['route' => 'admin.faqs.index', 'pattern' => 'admin.faqs.*', 'label' => 'FAQs', 'icon' => 'fa-circle-question'],
                ['route' => 'admin.galleries.index', 'pattern' => 'admin.galleries.*', 'label' => 'Media Gallery', 'icon' => 'fa-images'],
            ],
        ],
        [
            'id' => 'geography',
            'label' => 'Geography',
            'icon' => 'fa-flag',
            'items' => [
                ['route' => 'admin.countries.index', 'pattern' => 'admin.countries.*', 'label' => 'Countries', 'icon' => 'fa-flag'],
                ['route' => 'admin.cities.index', 'pattern' => 'admin.cities.*', 'label' => 'Cities', 'icon' => 'fa-city'],
            ],
        ],
        [
            'id' => 'bookings',
            'label' => 'Bookings',
            'icon' => 'fa-calendar-check',
            'items' => [
                ['route' => 'admin.course-sat-bookings.index', 'pattern' => 'admin.course-sat-bookings.*', 'label' => 'CourseSat Bookings', 'icon' => 'fa-calendar-check'],
            ],
        ],
        [
            'id' => 'affiliates',
            'label' => 'Affiliates',
            'icon' => 'fa-user-tie',
            'items' => [
                ['route' => 'admin.agents.index', 'pattern' => 'admin.agents.*', 'label' => 'Agents', 'icon' => 'fa-user-tie'],
                ['route' => 'admin.referral-settings.index', 'pattern' => 'admin.referral-settings.*', 'label' => 'Referral Settings', 'icon' => 'fa-sliders'],
                ['route' => 'admin.referral-commissions.index', 'pattern' => 'admin.referral-commissions.*', 'label' => 'Commissions', 'icon' => 'fa-hand-holding-dollar'],
                ['route' => 'admin.referral-attributions.index', 'pattern' => 'admin.referral-attributions.*', 'label' => 'Attributions', 'icon' => 'fa-link'],
                ['route' => 'admin.payout-requests.index', 'pattern' => 'admin.payout-requests.*', 'label' => 'Payouts', 'icon' => 'fa-money-check-dollar'],
            ],
        ],
        [
            'id' => 'finance',
            'label' => 'Finance',
            'icon' => 'fa-money-bill-transfer',
            'items' => [
                ['route' => 'admin.exchange-rates.index', 'pattern' => 'admin.exchange-rates.*', 'label' => 'Exchange Rates', 'icon' => 'fa-money-bill-transfer'],
                ['route' => 'admin.conversion-fees.index', 'pattern' => 'admin.conversion-fees.*', 'label' => 'Conversion Fees', 'icon' => 'fa-percent'],
                ['route' => 'admin.bank-accounts.index', 'pattern' => 'admin.bank-accounts.*', 'label' => 'Bank Accounts', 'icon' => 'fa-building-columns'],
            ],
        ],
        [
            'id' => 'system',
            'label' => 'System',
            'icon' => 'fa-gear',
            'items' => [
                ['route' => 'admin.users.index', 'pattern' => 'admin.users.*', 'label' => 'Users & Roles', 'icon' => 'fa-users'],
                ['route' => 'admin.settings.index', 'pattern' => 'admin.settings.*', 'label' => 'Settings', 'icon' => 'fa-gear'],
            ],
        ],
    ];

    $navActive = function ($pattern): bool {
        foreach ((array) $pattern as $p) {
            if (request()->routeIs($p)) {
                return true;
            }
        }

        return false;
    };

    $groupActive = function (array $group) use ($navActive): bool {
        foreach ($group['items'] as $item) {
            if ($navActive($item['pattern'])) {
                return true;
            }
        }

        return false;
    };

    $defaultOpen = [];
    foreach ($navGroups as $group) {
        $defaultOpen[$group['id']] = $groupActive($group);
    }
@endphp

@php
    $topLinks = [
        ['route' => 'admin.dashboard', 'pattern' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge'],
        ['route' => 'admin.applications.index', 'pattern' => 'admin.applications.*', 'label' => 'Applications', 'icon' => 'fa-file-signature'],
    ];
@endphp

<nav class="space-y-1 px-3" x-data="adminNavGroups(@js($defaultOpen))">
    {{-- Top-level links --}}
    @foreach ($topLinks as $link)
        @php $active = $navActive($link['pattern']); @endphp
        <a href="{{ route($link['route']) }}"
           class="{{ $active ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-300' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
            <i class="fa-solid {{ $link['icon'] }} w-6 text-center mr-2 {{ $active ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
            <span x-show="$store.adminLayout.sidebarOpen" x-cloak>{{ $link['label'] }}</span>
        </a>
    @endforeach

    {{-- Collapsible groups --}}
    <div class="pt-2 space-y-1">
        @foreach ($navGroups as $group)
            @php
                $activeGroup = $groupActive($group);
            @endphp
            <div class="rounded-md" x-data="{ id: '{{ $group['id'] }}' }">
                <button type="button"
                        @click="toggleGroup(id)"
                        class="w-full group flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-md transition-colors
                               {{ $activeGroup ? 'bg-slate-800 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <span class="flex items-center min-w-0">
                        <i class="fa-solid {{ $group['icon'] }} w-6 text-center mr-2 shrink-0 {{ $activeGroup ? 'text-primary-400' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                        <span x-show="$store.adminLayout.sidebarOpen" x-cloak class="truncate">{{ $group['label'] }}</span>
                    </span>
                    <i x-show="$store.adminLayout.sidebarOpen"
                       x-cloak
                       class="fa-solid fa-chevron-down text-[10px] text-slate-500 transition-transform duration-200 shrink-0 ml-2"
                       :class="isOpen(id) ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="$store.adminLayout.sidebarOpen && isOpen(id)"
                     x-collapse
                     x-cloak
                     class="mt-0.5 space-y-0.5 overflow-hidden">
                    @foreach ($group['items'] as $item)
                        @php $active = $navActive($item['pattern']); @endphp
                        <a href="{{ route($item['route']) }}"
                           class="{{ $active ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white text-slate-400' }} group flex items-center pl-9 pr-3 py-2 text-[13px] font-medium rounded-md transition-colors">
                            <i class="fa-solid {{ $item['icon'] }} w-5 text-center mr-2 text-xs {{ $active ? 'text-white' : 'text-slate-500 group-hover:text-slate-300' }}"></i>
                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</nav>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('adminNavGroups', (defaultOpen) => ({
        openGroups: { ...defaultOpen },

        init() {
            try {
                const saved = localStorage.getItem('adminNavGroups');
                if (saved) {
                    const parsed = JSON.parse(saved);
                    this.openGroups = { ...this.openGroups, ...parsed };
                }
            } catch (e) {}

            Object.keys(defaultOpen).forEach((id) => {
                if (defaultOpen[id]) {
                    this.openGroups[id] = true;
                }
            });

            this.$watch('openGroups', (value) => {
                localStorage.setItem('adminNavGroups', JSON.stringify(value));
            });
        },

        isOpen(id) {
            return !!this.openGroups[id];
        },

        toggleGroup(id) {
            if (!Alpine.store('adminLayout').sidebarOpen) {
                Alpine.store('adminLayout').sidebarOpen = true;
                this.openGroups[id] = true;
                return;
            }
            this.openGroups[id] = !this.openGroups[id];
        },
    }));
});
</script>
