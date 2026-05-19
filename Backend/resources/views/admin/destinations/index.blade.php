@extends('admin.layouts.layout')

@section('content')
    <div class="main-content app-content">
        <div class="container-fluid">
            <!-- Start::page-header -->
            <div class="my-4 page-header-breadcrumb flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <h1 class="page-title font-semibold text-lg text-defaulttextcolor dark:text-defaulttextcolor/70 mb-1">
                        Destinations</h1>
                    <nav>
                        <ol class="flex items-center whitespace-nowrap min-w-0 gap-2">
                            <li class="text-sm">
                                <a class="flex items-center text-primary hover:text-primary dark:text-primary"
                                    href="{{ route('admin.dashboard') }}">
                                    Dashboard
                                    <i class="ri-arrow-right-s-line text-base rtl:rotate-180"></i>
                                </a>
                            </li>
                            <li class="text-sm text-gray-500 dark:text-white/50 truncate" aria-current="page">Destinations
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.destinations.create') }}"
                        class="ti-btn ti-btn-primary btn-wave waves-effect waves-light">
                        <i class="ri-add-line"></i> Add Destination
                    </a>
                </div>
            </div>
            <!-- End::page-header -->

            <!-- Start::row-1 -->
            <div class="grid grid-cols-12 gap-6">
                @forelse ($destinations as $destination)
                    <div class="col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3">
                        <div class="box overflow-hidden relative group">
                            <div class="relative h-48 w-full">
                                @if($destination->image_url)
                                    <img src="{{ Str::startsWith($destination->image_url, 'http') ? $destination->image_url : Storage::url($destination->image_url) }}"
                                        alt="{{ $destination->name }}"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                    <div class="w-full h-full bg-gray-200 dark:bg-black/20 flex items-center justify-center">
                                        <i class="ri-map-pin-line text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2">
                                    @if($destination->is_active)
                                        <span
                                            class="badge bg-success/20 text-success backdrop-blur-sm shadow-sm ring-1 ring-success/30">Active</span>
                                    @else
                                        <span
                                            class="badge bg-danger/20 text-danger backdrop-blur-sm shadow-sm ring-1 ring-danger/30">Inactive</span>
                                    @endif
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/80 to-transparent">
                                    <h5 class="text-white font-bold text-lg mb-1">{{ $destination->name }}</h5>
                                    <span class="text-white/80 text-xs flex items-center gap-1">
                                        <i class="ri-global-line"></i> {{ $destination->region }}
                                    </span>
                                </div>
                            </div>
                            <div class="box-body space-y-3">
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="flex flex-col">
                                        <span class="text-muted text-xs">Visa Timeline</span>
                                        <span class="font-medium truncate">{{ $destination->visa_timeline ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-muted text-xs">Tuition Avg.</span>
                                        <span
                                            class="font-medium truncate">{{ Str::limit($destination->tuition_range, 15) ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div
                                    class="pt-3 border-t border-dashed border-defaultborder dark:border-defaultborder/10 flex justify-between items-center">
                                    <div class="text-xs text-muted">
                                        @if($destination->country)
                                            <span class="flex items-center gap-1"><i class="ri-flag-line"></i>
                                                {{ $destination->country->name }}</span>
                                        @endif
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.destinations.edit', $destination->id) }}"
                                            class="ti-btn ti-btn-sm ti-btn-soft-primary p-1 px-2">
                                            <i class="ri-edit-line"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.destinations.destroy', $destination->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this destination?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ti-btn ti-btn-sm ti-btn-soft-danger p-1 px-2">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-12">
                        <div class="box">
                            <div class="box-body text-center py-5">
                                <i class="ri-map-pin-line text-5xl text-gray-300 mb-3 block"></i>
                                <h5 class="text-muted">No Destinations Found</h5>
                                <p class="text-gray-400 text-sm mb-4">Get started by adding a new study destination.</p>
                                <a href="{{ route('admin.destinations.create') }}" class="ti-btn ti-btn-primary">
                                    <i class="ri-add-line"></i> Create Destination
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $destinations->links() }}
            </div>
            <!-- End::row-1 -->
        </div>
    </div>
@endsection