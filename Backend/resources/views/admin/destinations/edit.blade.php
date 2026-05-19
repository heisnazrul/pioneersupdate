@extends('admin.layouts.layout')

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Start::page-header -->
            <div class="my-4 page-header-breadcrumb flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="page-title font-semibold text-lg text-defaulttextcolor dark:text-defaulttextcolor/70 mb-1">
                        Edit Destination</h1>
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
                                    href="{{ route('admin.destinations.index') }}">
                                    Destinations
                                    <i class="ri-arrow-right-s-line text-base rtl:rotate-180"></i>
                                </a>
                            </li>
                            <li class="text-sm text-gray-500 dark:text-white/50 truncate" aria-current="page">Edit:
                                {{ $destination->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- End::page-header -->

            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12">
                    <form action="{{ route('admin.destinations.update', $destination->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('admin.destinations._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection