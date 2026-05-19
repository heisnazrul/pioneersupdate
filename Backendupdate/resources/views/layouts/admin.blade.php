<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Pioneers Edu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        primary: {
                            50: '#eff6ff', 100: '#dbeafe', 200: '#bfdbfe', 300: '#93c5fd', 400: '#60a5fa',
                            500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 800: '#1e40af', 900: '#1e3a8a', 950: '#172554',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <!-- Alpine.js for dropdowns and sidebar toggle -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full flex overflow-hidden bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm" x-data="{ sidebarOpen: true, profileOpen: false }">

    <!-- Sidebar -->
    <aside class="flex-shrink-0 w-64 bg-slate-900 text-slate-300 flex flex-col transition-all duration-300" :class="sidebarOpen ? 'w-64' : 'w-20'">
        <!-- Logo -->
        <div class="h-16 flex items-center px-6 bg-slate-950/50">
            <i class="fa-solid fa-graduation-cap text-primary-500 text-xl mr-3"></i>
            <span class="text-white font-bold text-lg tracking-wide whitespace-nowrap overflow-hidden transition-opacity" :class="sidebarOpen ? 'opacity-100' : 'opacity-0 w-0 hidden'">Pioneers Edu</span>
        </div>

        <!-- Nav Links -->
        <div class="flex-1 overflow-y-auto py-4 custom-scrollbar">
            <nav class="space-y-1 px-3">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-gauge w-6 text-center mr-2 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Dashboard</span>
                </a>

                <a href="{{ route('admin.applications.index') }}" class="{{ request()->routeIs('admin.applications.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-file-signature w-6 text-center mr-2 {{ request()->routeIs('admin.applications.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Applications</span>
                </a>

                <!-- Academic Header -->
                <div :class="sidebarOpen ? 'block' : 'hidden'" class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Academic
                </div>

                <a href="{{ route('admin.language-schools.index') }}" class="{{ request()->routeIs('admin.language-schools.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-school-flag w-6 text-center mr-2 {{ request()->routeIs('admin.language-schools.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Language Schools</span>
                </a>

                <a href="{{ route('admin.language-school-branches.index') }}" class="{{ request()->routeIs('admin.language-school-branches.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-code-branch w-6 text-center mr-2 {{ request()->routeIs('admin.language-school-branches.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">School Branches</span>
                </a>

                <a href="{{ route('admin.language-course-categories.index') }}" class="{{ request()->routeIs('admin.language-course-categories.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-layer-group w-6 text-center mr-2 {{ request()->routeIs('admin.language-course-categories.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Course Categories</span>
                </a>

                <a href="{{ route('admin.language-school-courses.index') }}" class="{{ request()->routeIs('admin.language-school-courses.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-book-open w-6 text-center mr-2 {{ request()->routeIs('admin.language-school-courses.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">School Courses</span>
                </a>

                <a href="{{ route('admin.language-online-courses.index') }}" class="{{ request()->routeIs('admin.language-online-courses.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-laptop-file w-6 text-center mr-2 {{ request()->routeIs('admin.language-online-courses.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Online Courses</span>
                </a>

                <a href="{{ route('admin.language-course-summer-camps.index') }}" class="{{ request()->routeIs('admin.language-course-summer-camps.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-campground w-6 text-center mr-2 {{ request()->routeIs('admin.language-course-summer-camps.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Summer Camps</span>
                </a>

                <a href="{{ route('admin.language-course-training-courses.index') }}" class="{{ request()->routeIs('admin.language-course-training-courses.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-briefcase w-6 text-center mr-2 {{ request()->routeIs('admin.language-course-training-courses.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Training Courses</span>
                </a>

                <a href="{{ route('admin.language-school-course-promotions.index') }}" class="{{ request()->routeIs('admin.language-school-course-promotions.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-tags w-6 text-center mr-2 {{ request()->routeIs('admin.language-school-course-promotions.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Course Promotions</span>
                </a>

                <a href="{{ route('admin.language-school-pickups.index') }}" class="{{ request()->routeIs('admin.language-school-pickups.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-plane-arrival w-6 text-center mr-2 {{ request()->routeIs('admin.language-school-pickups.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Airport Pickups</span>
                </a>

                <a href="{{ route('admin.language-school-insurances.index') }}" class="{{ request()->routeIs('admin.language-school-insurances.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-shield-heart w-6 text-center mr-2 {{ request()->routeIs('admin.language-school-insurances.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Student Insurance</span>
                </a>

                <a href="{{ route('admin.language-school-pioneers-discounts.index') }}" class="{{ request()->routeIs('admin.language-school-pioneers-discounts.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-percent w-6 text-center mr-2 {{ request()->routeIs('admin.language-school-pioneers-discounts.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Pioneers Discounts</span>
                </a>

                <div :class="sidebarOpen ? 'block' : 'hidden'" class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    University
                </div>

                <a href="{{ route('admin.universities.index') }}" class="{{ request()->routeIs('admin.universities.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-building-columns w-6 text-center mr-2 {{ request()->routeIs('admin.universities.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Universities</span>
                </a>

                <a href="{{ route('admin.university-course-catalogs.index') }}" class="{{ request()->routeIs('admin.university-course-catalogs.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-book-atlas w-6 text-center mr-2 {{ request()->routeIs('admin.university-course-catalogs.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Course Catalogs</span>
                </a>

                <a href="{{ route('admin.university-courses.index') }}" class="{{ request()->routeIs('admin.university-courses.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-graduation-cap w-6 text-center mr-2 {{ request()->routeIs('admin.university-courses.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">University Courses</span>
                </a>

                <a href="{{ route('admin.scholarships.index') }}" class="{{ request()->routeIs('admin.scholarships.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-award w-6 text-center mr-2 {{ request()->routeIs('admin.scholarships.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Scholarships</span>
                </a>

                <a href="{{ route('admin.destinations.index') }}" class="{{ request()->routeIs('admin.destinations.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-earth-americas w-6 text-center mr-2 {{ request()->routeIs('admin.destinations.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Destinations</span>
                </a>

                <a href="{{ route('admin.destination-guides.index') }}" class="{{ request()->routeIs('admin.destination-guides.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-file-pdf w-6 text-center mr-2 {{ request()->routeIs('admin.destination-guides.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Destination Guides</span>
                </a>

                <a href="{{ route('admin.university-accommodation-rooms.index') }}" class="{{ request()->routeIs('admin.university-accommodation-rooms.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-bed w-6 text-center mr-2 {{ request()->routeIs('admin.university-accommodation-rooms.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Accommodation Rooms</span>
                </a>

                <a href="{{ route('admin.featured-lists.index') }}" class="{{ request()->routeIs('admin.featured-lists.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-list-check w-6 text-center mr-2 {{ request()->routeIs('admin.featured-lists.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Featured Lists</span>
                </a>

                <a href="{{ route('admin.levels.index') }}" class="{{ request()->routeIs('admin.levels.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-layer-group w-6 text-center mr-2 {{ request()->routeIs('admin.levels.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Levels</span>
                </a>

                <a href="{{ route('admin.subject-areas.index') }}" class="{{ request()->routeIs('admin.subject-areas.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-diagram-project w-6 text-center mr-2 {{ request()->routeIs('admin.subject-areas.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Subject Areas</span>
                </a>

                <a href="{{ route('admin.intake-terms.index') }}" class="{{ request()->routeIs('admin.intake-terms.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-calendar-days w-6 text-center mr-2 {{ request()->routeIs('admin.intake-terms.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Intake Terms</span>
                </a>

                <a href="{{ route('admin.uni-applications.index') }}" class="{{ request()->routeIs('admin.uni-applications.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-file-circle-check w-6 text-center mr-2 {{ request()->routeIs('admin.uni-applications.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Uni Applications</span>
                </a>

                <a href="{{ route('admin.scholarship-applications.index') }}" class="{{ request()->routeIs('admin.scholarship-applications.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-file-invoice w-6 text-center mr-2 {{ request()->routeIs('admin.scholarship-applications.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Scholarship Applications</span>
                </a>

                <a href="{{ route('admin.university-wishlists.index') }}" class="{{ request()->routeIs('admin.university-wishlists.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-heart w-6 text-center mr-2 {{ request()->routeIs('admin.university-wishlists.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">University Wishlists</span>
                </a>

                <!-- Accommodations Header -->
                <div :class="sidebarOpen ? 'block' : 'hidden'" class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Accommodations
                </div>

                <a href="{{ route('admin.language-school-accommodations.index') }}" class="{{ request()->routeIs('admin.language-school-accommodations.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-bed w-6 text-center mr-2 {{ request()->routeIs('admin.language-school-accommodations.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Manage Accommodations</span>
                </a>

                <a href="{{ route('admin.accommodation-types.index') }}" class="{{ request()->routeIs('admin.accommodation-types.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-house-user w-6 text-center mr-2 {{ request()->routeIs('admin.accommodation-types.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Accommodation Types</span>
                </a>

                <a href="{{ route('admin.meal-plans.index') }}" class="{{ request()->routeIs('admin.meal-plans.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-utensils w-6 text-center mr-2 {{ request()->routeIs('admin.meal-plans.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Meal Plans</span>
                </a>

                <a href="{{ route('admin.bedroom-types.index') }}" class="{{ request()->routeIs('admin.bedroom-types.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-door-open w-6 text-center mr-2 {{ request()->routeIs('admin.bedroom-types.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Bedroom Types</span>
                </a>

                <a href="{{ route('admin.bathroom-types.index') }}" class="{{ request()->routeIs('admin.bathroom-types.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-toilet w-6 text-center mr-2 {{ request()->routeIs('admin.bathroom-types.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Bathroom Types</span>
                </a>

                <!-- Content Header -->
                <div :class="sidebarOpen ? 'block' : 'hidden'" class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Content
                </div>

                <a href="{{ route('admin.blogs.index') }}" class="{{ request()->routeIs('admin.blogs.*') || request()->routeIs('admin.categories.*') || request()->routeIs('admin.blog-tags.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-newspaper w-6 text-center mr-2 {{ request()->routeIs('admin.blogs.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Blog & News</span>
                </a>

                <a href="{{ route('admin.reviews.index') }}" class="{{ request()->routeIs('admin.reviews.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-star w-6 text-center mr-2 {{ request()->routeIs('admin.reviews.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Reviews</span>
                </a>

                <a href="{{ route('admin.contact-submissions.index') }}" class="{{ request()->routeIs('admin.contact-submissions.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-envelope w-6 text-center mr-2 {{ request()->routeIs('admin.contact-submissions.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Inquiries</span>
                </a>

                <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-circle-question w-6 text-center mr-2 {{ request()->routeIs('admin.faqs.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">FAQs</span>
                </a>

                <a href="{{ route('admin.galleries.index') }}" class="{{ request()->routeIs('admin.galleries.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-images w-6 text-center mr-2 {{ request()->routeIs('admin.galleries.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Media Gallery</span>
                </a>

                <!-- Geography Header -->
                <div :class="sidebarOpen ? 'block' : 'hidden'" class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Geography
                </div>

                <a href="{{ route('admin.countries.index') }}" class="{{ request()->routeIs('admin.countries.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-flag w-6 text-center mr-2 {{ request()->routeIs('admin.countries.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Countries</span>
                </a>

                <a href="{{ route('admin.cities.index') }}" class="{{ request()->routeIs('admin.cities.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-city w-6 text-center mr-2 {{ request()->routeIs('admin.cities.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Cities</span>
                </a>

                <!-- System Header -->
                <div :class="sidebarOpen ? 'block' : 'hidden'" class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    System
                </div>

                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-users w-6 text-center mr-2 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Users & Roles</span>
                </a>

                <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-gear w-6 text-center mr-2 {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Settings</span>
                </a>

                <!-- Finance Header -->
                <div :class="sidebarOpen ? 'block' : 'hidden'" class="pt-4 pb-2 px-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    Finance
                </div>

                <a href="{{ route('admin.exchange-rates.index') }}" class="{{ request()->routeIs('admin.exchange-rates.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-money-bill-transfer w-6 text-center mr-2 {{ request()->routeIs('admin.exchange-rates.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Exchange Rates</span>
                </a>

                <a href="{{ route('admin.conversion-fees.index') }}" class="{{ request()->routeIs('admin.conversion-fees.*') ? 'bg-primary-600 text-white' : 'hover:bg-slate-800 hover:text-white' }} group flex items-center px-3 py-2.5 text-sm font-medium rounded-md transition-colors">
                    <i class="fa-solid fa-percent w-6 text-center mr-2 {{ request()->routeIs('admin.conversion-fees.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}"></i>
                    <span :class="sidebarOpen ? 'block' : 'hidden'">Conversion Fees</span>
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50 dark:bg-gray-900">
        
        <!-- Header -->
        <header class="bg-white dark:bg-gray-800 shadow-sm h-16 flex items-center justify-between px-6 z-10">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none mr-4">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 hidden sm:block">@yield('header', 'Overview')</h2>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" @click.away="open = false" class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 focus:outline-none">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                    @else
                        <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                    <span class="hidden md:block">{{ auth()->user()->name }}</span>
                    <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" x-transition.opacity class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 border border-gray-200 dark:border-gray-700 z-50" x-cloak>
                    <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('admin.users.edit', auth()->id()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">Profile</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 p-4 m-6 mb-0 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-400">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 m-6 mb-0 rounded shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 dark:text-red-400">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Content Scrollable Area -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
        
    </div>

</body>
</html>
