@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-25 md:gap-58 !pb-50">
            <div class="flex flex-col items-center gap-2 sm:flex-row sm:justify-between justify-center page-header-content">
                <h1 class="text-xl font-semibold text-defaulttextcolor text-defaultsize">Edit Application</h1>
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
                            <a class="flex items-center text-defaulttextcolor hover:text-primary"
                                href="{{ route('admin.scholarship-applications.index') }}">
                                Applications
                                <i
                                    class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 rtl:rotate-180"></i>
                            </a>
                        </li>
                        <li class="text-sm">
                            <a class="flex items-center text-defaulttextcolor font-semibold" href="javascript:void(0);">
                                Edit #{{ $scholarshipApplication->application_id }}
                            </a>
                        </li>
                    </ol>
                </nav>
            </div>

            <div class="grid grid-cols-12 gap-x-6">
                <!-- Application Details -->
                <div class="col-span-12 xl:col-span-8">
                    <div class="box">
                        <div class="box-header">
                            <div class="box-title">Applicant Details</div>
                        </div>
                        <div class="box-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full
                                        Name</label>
                                    <p class="text-gray-900 dark:text-white font-semibold">
                                        {{ $scholarshipApplication->first_name }} {{ $scholarshipApplication->last_name }}
                                    </p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                                    <p class="text-gray-900 dark:text-white">{{ $scholarshipApplication->email }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone</label>
                                    <p class="text-gray-900 dark:text-white">{{ $scholarshipApplication->phone }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Country</label>
                                    <p class="text-gray-900 dark:text-white">{{ $scholarshipApplication->country ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            <hr class="my-6 border-gray-200 dark:border-white/10">

                            <h4 class="font-bold text-gray-800 dark:text-white mb-4">Academic Profile</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Education
                                        Level</label>
                                    <p class="text-gray-900 dark:text-white">
                                        {{ $scholarshipApplication->education_level ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Grade
                                        Average</label>
                                    <p class="text-gray-900 dark:text-white">
                                        {{ $scholarshipApplication->grade_average ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">English
                                        Proficiency</label>
                                    <p class="text-gray-900 dark:text-white">
                                        {{ $scholarshipApplication->english_proficiency ?? 'None' }}</p>
                                </div>
                            </div>

                            @if($scholarshipApplication->notes)
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Applicant
                                        Notes</label>
                                    <div class="p-3 bg-gray-50 dark:bg-white/5 rounded text-gray-700 dark:text-gray-300">
                                        {{ $scholarshipApplication->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Management Sidebar -->
                <div class="col-span-12 xl:col-span-4">
                    <form action="{{ route('admin.scholarship-applications.update', $scholarshipApplication->id) }}"
                        method="POST">
                        @csrf
                        @method('PUT')

                        <div class="box">
                            <div class="box-header">
                                <div class="box-title">Management</div>
                            </div>
                            <div class="box-body space-y-4">

                                <!-- Scholarship Info -->
                                <div class="p-3 bg-primary/5 rounded border border-primary/10">
                                    <label class="block text-xs font-bold text-primary uppercase mb-1">Applying For</label>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $scholarshipApplication->scholarship_title }}</p>
                                </div>

                                <!-- Status -->
                                <div>
                                    <label for="status"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Application
                                        Status</label>
                                    <select name="status" id="status" class="ti-form-select rounded-sm py-2 px-3">
                                        <option value="pending" {{ $scholarshipApplication->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="reviewing" {{ $scholarshipApplication->status == 'reviewing' ? 'selected' : '' }}>Reviewing</option>
                                        <option value="approved" {{ $scholarshipApplication->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $scholarshipApplication->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>

                                <!-- Assignee -->
                                <div>
                                    <label for="assignee_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assigned
                                        To</label>
                                    <select name="assignee_id" id="assignee_id" class="ti-form-select rounded-sm py-2 px-3">
                                        <option value="">Unassigned</option>
                                        @foreach($assignees as $assignee)
                                            <option value="{{ $assignee->id }}" {{ $scholarshipApplication->assignee_id == $assignee->id ? 'selected' : '' }}>
                                                {{ $assignee->name }} ({{ ucfirst($assignee->role) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Admin Notes -->
                                <div>
                                    <label for="admin_notes"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin Notes
                                        (Internal)</label>
                                    <textarea name="notes" id="admin_notes" rows="4"
                                        class="ti-form-input rounded-sm">{{ $scholarshipApplication->notes }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">This will update the notes field.</p>
                                </div>

                                <div class="pt-4 border-t border-gray-100 dark:border-white/10">
                                    <button type="submit" class="ti-btn ti-btn-primary w-full justify-center">Update
                                        Application</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection