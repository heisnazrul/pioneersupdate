@extends('admin.layouts.layout')

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Start::page-header -->
            <div class="my-4 page-header-breadcrumb flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="page-title font-semibold text-lg text-defaulttextcolor dark:text-defaulttextcolor/70 mb-1">
                        Destination Guides</h1>
                    <nav>
                        <ol class="flex items-center whitespace-nowrap min-w-0 gap-2">
                            <li class="text-sm">
                                <a class="flex items-center text-primary hover:text-primary dark:text-primary"
                                    href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                    <i class="ri-arrow-right-s-line text-base rtl:rotate-180"></i>
                                </a>
                            </li>
                            <li class="text-sm text-gray-500 dark:text-white/50 truncate" aria-current="page">Destination
                                Guides
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.destination-guides.create') }}"
                        class="ti-btn ti-btn-primary btn-wave waves-effect waves-light">
                        <i class="ri-add-line"></i> Add Guide
                    </a>
                </div>
            </div>
            <!-- End::page-header -->

            <!-- Start::row-1 -->
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <div class="box">
                        <div class="box-header justify-between">
                            <div class="box-title">
                                All Guides
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-nowrap w-full">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Destination</th>
                                            <th>Title</th>
                                            <th>Year</th>
                                            <th>Status</th>
                                            <th>File</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($guides as $guide)
                                            <tr>
                                                <td>{{ $guide->id }}</td>
                                                <td>{{ $guide->destination->name }}</td>
                                                <td>{{ $guide->title }}</td>
                                                <td>{{ $guide->year }}</td>
                                                <td>
                                                    @if($guide->is_active)
                                                        <span class="badge bg-success/10 text-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger/10 text-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ Storage::url($guide->file_path) }}" target="_blank"
                                                        class="text-primary hover:underline">
                                                        <i class="ri-file-pdf-line"></i> View
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('admin.destination-guides.edit', $guide->id) }}"
                                                            class="ti-btn ti-btn-sm ti-btn-info">
                                                            <i class="ri-edit-line"></i>
                                                        </a>
                                                        <form
                                                            action="{{ route('admin.destination-guides.destroy', $guide->id) }}"
                                                            method="POST" onsubmit="return confirm('Delete this guide?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-danger">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">No guides found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End::row-1 -->
        </div>
    </div>
@endsection