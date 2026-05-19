@extends('admin.layouts.layout')

@section('content')
    <div class="main-content">
        <!-- Page Header -->
        <div class="block justify-between page-header md:flex">
            <div>
                <h3
                    class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                    Edit Page: {{ $cmsPage->title }}</h3>
            </div>
            <ol class="flex items-center whitespace-nowrap min-w-0">
                <li class="text-sm">
                    <a class="flex items-center text-primary hover:text-primary dark:text-primary"
                        href="{{ route('admin.dashboard') }}">
                        Dashboard
                        <i
                            class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-white/10 rtl:rotate-180"></i>
                    </a>
                </li>
                <li class="text-sm">
                    <a class="flex items-center text-primary hover:text-primary dark:text-primary"
                        href="{{ route('admin.cms-pages.index') }}">
                        CMS Pages
                        <i
                            class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-white/10 rtl:rotate-180"></i>
                    </a>
                </li>
                <li class="text-sm">
                    <a class="flex items-center text-gray-500 hover:text-primary dark:text-white/70"
                        href="javascript:void(0);">Edit</a>
                </li>
            </ol>
        </div>
        <!-- End Page Header -->

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <div class="box">
                    <div class="box-header">
                        <div class="box-title">Page Content</div>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('admin.cms-pages.update', $cmsPage->slug) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @php
                                $inputClass = "mt-1 block w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:border-primary focus:ring focus:ring-primary dark:bg-black/20 dark:border-white/10 dark:text-white";
                                $labelClass = "block text-sm font-medium text-gray-700 dark:text-white mb-2";
                            @endphp

                            <div class="grid grid-cols-12 gap-4 mb-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label for="title" class="{{ $labelClass }}">Page Title</label>
                                    <input type="text" class="{{ $inputClass }}" id="title" name="title"
                                        value="{{ old('title', $cmsPage->title) }}" required>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="sub_title" class="{{ $labelClass }}">Sub Title</label>
                                    <input type="text" class="{{ $inputClass }}" id="sub_title" name="sub_title"
                                        value="{{ old('sub_title', $cmsPage->sub_title) }}">
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-4 mb-4">
                                <div class="col-span-12 md:col-span-6">
                                    <label for="meta_title" class="{{ $labelClass }}">Meta Title</label>
                                    <input type="text" class="{{ $inputClass }}" id="meta_title" name="meta_title"
                                        value="{{ old('meta_title', $cmsPage->meta_title) }}">
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <label for="meta_description" class="{{ $labelClass }}">Meta Description</label>
                                    <textarea class="{{ $inputClass }}" id="meta_description" name="meta_description"
                                        rows="2">{{ old('meta_description', $cmsPage->meta_description) }}</textarea>
                                </div>
                            </div>

                            <div class="py-4">
                                <hr class="border-gray-200 dark:border-white/10">
                            </div>

                            <h6 class="font-bold mb-4 text-gray-800 dark:text-white">Page Content Sections</h6>

                            {{-- Load specific form based on slug --}}
                            @includeIf('admin.cms_pages.forms.' . $cmsPage->slug)

                            <div class="mt-4">
                                <button type="submit" class="ti-btn ti-btn-primary-full">Update Page</button>
                                <a href="{{ route('admin.cms-pages.index') }}" class="ti-btn ti-btn-light">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection