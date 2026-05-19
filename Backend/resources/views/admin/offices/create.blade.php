@extends('admin.layouts.layout')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex mb-1" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="ri-arrow-right-s-line text-gray-400"></i>
                                <a href="{{ route('admin.offices.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white md:ml-2">Offices</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="ri-arrow-right-s-line text-gray-400"></i>
                                <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">Add New</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add New Office</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Form -->
            <div class="lg:col-span-2 space-y-6">
                <form action="{{ route('admin.offices.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Office Details Card -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Office Details</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Basic information about the office location.</p>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City <span class="text-red-500">*</span></label>
                                <input type="text" name="city" id="city" value="{{ old('city') }}" required
                                    onkeyup="generateSlug(this.value)" placeholder="e.g. London"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                            </div>
                            <div>
                                <label for="ar_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City (Arabic)</label>
                                <input type="text" name="ar_city" id="ar_city" value="{{ old('ar_city') }}" placeholder="المدينة"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                            </div>

                            <!-- Slug -->
                            <div>
                                <label for="slug" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
                                <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required readonly
                                    class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50 text-gray-500 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300 sm:text-sm px-3 py-2 cursor-not-allowed">
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country <span class="text-red-500">*</span></label>
                                <input type="text" name="country" id="country" value="{{ old('country') }}" required placeholder="e.g. United Kingdom"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                            </div>
                            <div>
                                <label for="ar_country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country (Arabic)</label>
                                <input type="text" name="ar_country" id="ar_country" value="{{ old('ar_country') }}" placeholder="الدولة"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Office Type</label>
                                <select name="type" id="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                                    <option value="Branch Office">Branch Office</option>
                                    <option value="Headquarters">Headquarters</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description" id="description" rows="3" placeholder="Brief description..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">{{ old('description') }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label for="ar_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (Arabic)</label>
                                <textarea name="ar_description" id="ar_description" rows="3" placeholder="وصف المكتب"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">{{ old('ar_description') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Contact & Check-in -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
                         <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Contact & Location</h3>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                             <!-- Email -->
                             <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address <span class="text-red-500">*</span></label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-mail-line text-gray-400"></i>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="office@example.com"
                                        class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                                </div>
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number <span class="text-red-500">*</span></label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ri-phone-line text-gray-400"></i>
                                    </div>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="+1 234 567 8900"
                                        class="block w-full pl-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Address <span class="text-red-500">*</span></label>
                                <textarea name="address" id="address" rows="2" required placeholder="Street address, City, Zip Code"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">{{ old('address') }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label for="ar_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Address (Arabic)</label>
                                <textarea name="ar_address" id="ar_address" rows="2" placeholder="العنوان الكامل"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">{{ old('ar_address') }}</textarea>
                            </div>

                            <!-- Hours -->
                            <div>
                                <label for="hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening Hours</label>
                                <input type="text" name="hours" id="hours" value="{{ old('hours') }}" placeholder="Mon-Fri 9AM - 6PM"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                            </div>
                            <div>
                                <label for="ar_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Opening Hours (Arabic)</label>
                                <input type="text" name="ar_hours" id="ar_hours" value="{{ old('ar_hours') }}" placeholder="ساعات العمل"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                            </div>

                            <!-- Map URL -->
                            <div>
                                <label for="map_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Map Link</label>
                                <input type="url" name="map_url" id="map_url" value="{{ old('map_url') }}" placeholder="https://goo.gl/maps/..."
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm px-3 py-2">
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                             <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Office Image</h3>
                        </div>
                        <div class="p-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Photo</label>
                            <input type="file" name="image" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300">
                            <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF up to 2MB. Recommended 800x600px.</p>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Office
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Information</h3>
                    </div>
                    <div class="p-6 text-sm text-gray-500 dark:text-gray-400 space-y-4">
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white mb-1">Slug Generation</p>
                            <p>The slug is automatically generated from the city name but must be unique across all offices.</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white mb-1">Office Image</p>
                            <p>Upload a high-quality image of the office exterior or team. This will be displayed on the public contact page.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function generateSlug(val) {
            document.getElementById('slug').value = val.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '') + '-office';
        }
    </script>
@endsection
