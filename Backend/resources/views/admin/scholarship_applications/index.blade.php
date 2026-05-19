@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-25 md:gap-58 !pb-50">
            <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-between justify-center page-header-content">
                <h1 class="text-xl font-semibold text-defaulttextcolor text-defaultsize">Scholarship Applications</h1>
                <nav aria-label="Breadcrumb">
                    <ol class="flex items-center whitespace-nowrap min-w-0">
                        <li class="text-sm">
                            <a class="flex items-center text-defaulttextcolor hover:text-primary"
                                href="{{ route('admin.dashboard') }}">
                                Dashboard
                                <i
                                    class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 rtl:rotate-180"></i>
                            </a>
                        </li>
                        <li class="text-sm">
                            <a class="flex items-center text-defaulttextcolor font-semibold" href="javascript:void(0);">
                                Scholarship Applications
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="grid grid-cols-12 gap-x-6">
                <div class="col-span-12">
                    <div class="box">
                        <div class="box-header justify-between">
                            <div class="box-title">
                                All Applications
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <form method="GET" class="flex gap-2">
                                    <div class="relative">
                                        <select name="status" class="ti-form-select rounded-sm py-2 px-3 text-sm"
                                            onchange="this.form.submit()">
                                            <option value="">All Statuses</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                                                Approved</option>
                                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                                Rejected</option>
                                        </select>
                                    </div>
                                    <div class="relative">
                                        <input type="text" name="search" class="ti-form-input rounded-sm py-2 px-3 text-sm"
                                            placeholder="Search..." value="{{ request('search') }}">
                                    </div>
                                    <button class="ti-btn ti-btn-primary py-2 px-3" type="submit">
                                        <i class="ti ti-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="overflow-x-auto">
                                <table class="ti-custom-table ti-custom-table-head whitespace-nowrap">
                                    <thead class="bg-gray-50 dark:bg-black/20">
                                        <tr>
                                            <th scope="col" class="dark:text-white/80 font-bold">ID</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Scholarship</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Applicant</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Education</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Status</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Date</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                        @forelse($applications as $app)
                                            <tr>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        class="text-primary font-semibold hover:underline">
                                                        #{{ $app->application_id }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="block font-medium text-gray-800 dark:text-white text-sm">
                                                        {{ $app->scholarship_title }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="flex items-center gap-2">
                                                        <span class="avatar avatar-xs rounded-full bg-primary/10 text-primary">
                                                            {{ substr($app->first_name, 0, 1) }}
                                                        </span>
                                                        <div>
                                                            <span
                                                                class="block font-medium text-gray-800 dark:text-white text-sm">{{ $app->first_name }}
                                                                {{ $app->last_name }}</span>
                                                            <span
                                                                class="block text-xs text-gray-500 dark:text-white/70">{{ $app->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span
                                                        class="block text-sm text-gray-800 dark:text-white">{{ $app->education_level }}</span>
                                                    <span class="text-xs text-gray-500">GPA: {{ $app->grade_average }}</span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColor = match ($app->status) {
                                                            'approved' => 'success',
                                                            'rejected' => 'danger',
                                                            'reviewing' => 'info',
                                                            'pending' => 'warning',
                                                            default => 'primary'
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $statusColor }}/10 text-{{ $statusColor }} px-2.5 py-0.5 rounded-sm text-xs font-medium">
                                                        {{ ucfirst($app->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-gray-500 text-xs">{{ $app->created_at->format('d M Y') }}</span>
                                                </td>
                                                <td>
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('admin.scholarship-applications.edit', $app->id) }}"
                                                            class="ti-btn ti-btn-icon ti-btn-sm ti-btn-soft-info">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                        {{-- Add show/edit buttons when views are ready --}}
                                                        {{--
                                                        <a href="{{ route('admin.scholarship-applications.show', $app->id) }}"
                                                            class="ti-btn ti-btn-icon ti-btn-sm ti-btn-soft-primary">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        --}}
                                                        <form
                                                            action="{{ route('admin.scholarship-applications.destroy', $app->id) }}"
                                                            method="POST" onsubmit="return confirm('Are you sure?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="ti-btn ti-btn-icon ti-btn-sm ti-btn-soft-danger">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center p-4 text-gray-500">No scholarship
                                                    applications found.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 px-4 pb-4">
                                {{ $applications->withQueryString()->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection