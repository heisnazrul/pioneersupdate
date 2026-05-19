@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-25 md:gap-58 !pb-50">
            <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-between justify-center page-header-content">
                <h1 class="text-xl font-semibold text-defaulttextcolor text-defaultsize">Applications</h1>
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
                                Applications
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
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                        <option value="reviewing" {{ request('status') == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                        <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>
                                            Accepted</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                            Rejected</option>
                                        <option value="invalid" {{ request('status') == 'invalid' ? 'selected' : '' }}>
                                            Invalid</option>
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
                                            <th scope="col" class="dark:text-white/80 font-bold">Applicant</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Education</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Nationality</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Status</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Assigned To</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Date</th>
                                            <th scope="col" class="dark:text-white/80 font-bold">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                        @forelse($applications as $app)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.applications.show', $app->id) }}"
                                                        class="text-primary font-semibold hover:underline">
                                                        #{{ $app->application_id }}
                                                    </a>
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
                                                        class="block text-sm text-gray-800 dark:text-white">{{ $app->highest_education }}</span>
                                                    <span class="text-xs text-gray-500">GPA: {{ $app->grade_average }}</span>
                                                </td>
                                                <td>{{ $app->nationality }}</td>
                                                <td>
                                                    @php
                                                        $statusColor = match ($app->status) {
                                                            'accepted' => 'success',
                                                            'rejected' => 'danger',
                                                            'submitted' => 'info',
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
                                                    {{ $app->assignee ? $app->assignee->name : '-' }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-gray-500 text-xs">{{ $app->created_at->format('d M Y') }}</span>
                                                </td>
                                                <td>
                                                    <div class="flex gap-2">
                                                        <a href="{{ route('admin.applications.show', $app->id) }}"
                                                            class="ti-btn ti-btn-icon ti-btn-sm ti-btn-soft-primary">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                        <a href="{{ route('admin.applications.edit', $app->id) }}"
                                                            class="ti-btn ti-btn-icon ti-btn-sm ti-btn-soft-info">
                                                            <i class="ti ti-pencil"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center p-4 text-gray-500">No applications found.
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
