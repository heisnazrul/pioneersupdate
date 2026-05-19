@extends('admin.layouts.layout')

@section('content')
<div class="main-content">
    <!-- Page Header -->
    <div class="block justify-between page-header md:flex">
        <div>
            <h3 class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold"> CMS Pages</h3>
        </div>
        <ol class="flex items-center whitespace-nowrap min-w-0">
            <li class="text-sm">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary" href="{{ route('admin.dashboard') }}">
                    Dashboard
                    <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-white/10 rtl:rotate-180"></i>
                </a>
            </li>
            <li class="text-sm">
                <a class="flex items-center text-gray-500 hover:text-primary dark:text-white/70" href="javascript:void(0);">CMS Pages</a>
            </li>
        </ol>
    </div>
    <!-- End Page Header -->

    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12">
            <div class="box">
                <div class="box-header justify-between">
                    <div class="box-title">
                        All Pages
                    </div>
                </div>
                <div class="box-body p-0">
                    <div class="overflow-x-auto">
                        <table class="ti-custom-table ti-custom-table-head">
                            <thead>
                                <tr>
                                    <th scope="col">Page Name</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Last Updated</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pages as $page)
                                    <tr>
                                        <td>
                                            <div class="flex items-center">
                                                <span class="avatar avatar-sm rounded-full bg-light me-2">
                                                    <img src="{{ asset('assets/img/brand-logos/toggle-logo.png') }}" alt="img">
                                                </span>
                                                <span class="font-semibold">{{ $page->title }}</span>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-light text-dark rounded-sm">{{ $page->slug }}</span></td>
                                        <td>
                                            @if($page->is_active)
                                                <span class="badge bg-success/10 text-success rounded-sm">Active</span>
                                            @else
                                                <span class="badge bg-danger/10 text-danger rounded-sm">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $page->updated_at->format('d M Y, h:i A') }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.cms-pages.edit', $page->slug) }}" class="ti-btn ti-btn-icon ti-btn-sm ti-btn-soft-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">No pages found. Run seeder to populate.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection