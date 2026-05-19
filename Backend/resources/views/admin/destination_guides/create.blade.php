@extends('admin.layouts.layout')

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">

            <!-- Page Header -->
            <div class="my-4 page-header-breadcrumb flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="page-title font-semibold text-lg text-defaulttextcolor dark:text-defaulttextcolor/70 mb-1">
                        Add Destination Guide</h1>
                    <nav>
                        <ol class="flex items-center whitespace-nowrap min-w-0 gap-2">
                            <li class="text-sm">
                                <a class="flex items-center text-primary hover:text-primary dark:text-primary"
                                    href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                    <i class="ri-arrow-right-s-line text-base rtl:rotate-180"></i>
                                </a>
                            </li>
                            <li class="text-sm">
                                <a class="flex items-center text-primary hover:text-primary dark:text-primary"
                                    href="{{ route('admin.destination-guides.index') }}">
                                    Destination Guides
                                    <i class="ri-arrow-right-s-line text-base rtl:rotate-180"></i>
                                </a>
                            </li>
                            <li class="text-sm text-gray-500 dark:text-white/50 truncate" aria-current="page">Add New</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <form action="{{ route('admin.destination-guides.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-12 gap-6">
                    <!-- Left Column: Main Content -->
                    <div class="col-span-12 xl:col-span-8 space-y-6">
                        <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                            <div
                                class="box-header !border-b !border-gray-100 dark:!border-white/10 !py-4 !px-5 bg-gray-50/50 dark:bg-white/5">
                                <div
                                    class="box-title text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                    <i class="ri-book-open-line text-primary"></i> Guide Details
                                </div>
                            </div>
                            <div class="box-body !p-6 space-y-6">
                                <div class="grid grid-cols-12 gap-6">
                                    <div class="col-span-12">
                                        <label for="destination_id"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Destination
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors">
                                                <i class="ri-map-pin-line text-lg"></i>
                                            </span>
                                            <select name="destination_id" id="destination_id" style="padding-left: 2.3rem"
                                                class="form-control bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                                required>
                                                <option value="">Select Destination</option>
                                                @foreach($destinations as $destination)
                                                    <option value="{{ $destination->id }}" {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                                        {{ $destination->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <label for="title"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Guide
                                            Title <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors">
                                                <i class="ri-text text-lg"></i>
                                            </span>
                                            <input type="text"
                                                class="form-control pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                                id="title" name="title" value="{{ old('title') }}"
                                                placeholder="e.g. 2024 Guide to Studying in UK" required>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <label for="ar_title"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Guide Title (Arabic)</label>
                                        <input type="text"
                                            class="form-control pl-3 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                            id="ar_title" name="ar_title" value="{{ old('ar_title') }}"
                                            placeholder="عنوان الدليل">
                                    </div>

                                    <div class="col-span-12 md:col-span-6">
                                        <label for="year"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Year
                                            <span class="text-red-500">*</span></label>
                                        <div class="relative group">
                                            <span
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400 group-hover:text-primary transition-colors">
                                                <i class="ri-calendar-line text-lg"></i>
                                            </span>
                                            <input type="number"
                                                class="form-control pl-9 bg-gray-50 border-gray-200 focus:bg-white hover:border-primary focus:border-primary focus:ring focus:ring-primary/20 transition-all rounded-md text-sm py-2.5"
                                                id="year" name="year" value="{{ old('year', date('Y')) }}" min="2020"
                                                max="{{ date('Y') + 1 }}" required>
                                        </div>
                                    </div>

                                    <div class="col-span-12">
                                        <label for="file"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">PDF File
                                            <span class="text-red-500">*</span></label>
                                        <label for="file"
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <i class="ri-upload-cloud-2-line text-3xl text-gray-400 mb-2"></i>
                                                <p class="text-sm text-gray-500"><span class="font-semibold">Click to
                                                        upload</span> or drag and drop</p>
                                                <p class="text-xs text-gray-400">PDF (MAX. 10MB)</p>
                                            </div>
                                            <input id="file" name="file" type="file" class="hidden" accept="application/pdf"
                                                required
                                                onchange="document.getElementById('file-name').innerText = this.files[0].name" />
                                        </label>
                                        <p id="file-name" class="text-xs text-primary mt-2 text-center font-medium"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Actions -->
                    <div class="col-span-12 xl:col-span-4 space-y-6">
                        <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                            <div class="box-body p-4">
                                <div class="flex flex-col gap-4">
                                    <div class="flex items-stretch gap-3 h-10">
                                        <button type="submit"
                                            class="ti-btn ti-btn-primary flex-grow justify-center !text-base !py-0 !m-0 flex items-center">
                                            <i class="ri-save-line mr-2"></i> Create Guide
                                        </button>

                                        <button type="button" id="visibility_btn"
                                            class="flex items-center justify-center w-10 h-full rounded-md border transition-all bg-primary border-primary text-white"
                                            title="Toggle Visibility">
                                            <i class="ri-check-line text-xl font-bold"></i>
                                        </button>
                                        <input type="hidden" name="is_active" id="is_active_hidden" value="1">
                                    </div>
                                    <p class="text-xs text-muted text-center">Status: <span id="status_text"
                                            class="font-bold text-success">Active</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const visibilityBtn = document.getElementById('visibility_btn');
        const hiddenInput = document.getElementById('is_active_hidden');
        const statusText = document.getElementById('status_text');
        const icon = visibilityBtn.querySelector('i');

        visibilityBtn.addEventListener('click', function () {
            const isVisible = hiddenInput.value === '1';
            if (isVisible) {
                hiddenInput.value = '0';
                visibilityBtn.className = 'flex items-center justify-center w-10 h-full rounded-md border transition-all bg-gray-50 border-gray-200 text-gray-400 hover:border-primary hover:text-primary';
                icon.className = 'ri-eye-off-line text-lg';
                statusText.innerText = 'Hidden';
                statusText.className = 'font-bold text-muted';
            } else {
                hiddenInput.value = '1';
                visibilityBtn.className = 'flex items-center justify-center w-10 h-full rounded-md border transition-all bg-primary border-primary text-white';
                icon.className = 'ri-check-line text-xl font-bold';
                statusText.innerText = 'Active';
                statusText.className = 'font-bold text-success';
            }
        });
    </script>
@endsection
