@extends('admin.layouts.layout')

@section('content')
    <div class="main-content">
        <!-- Page Header -->
        <div class="block justify-between page-header md:flex">
            <div>
                <h3
                    class="!text-defaulttextcolor dark:!text-defaulttextcolor/70 dark:text-white dark:hover:text-white text-[1.125rem] font-semibold">
                    Contact Inquiries
                </h3>
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
                    <a class="flex items-center text-gray-500 hover:text-primary dark:text-white/70"
                        href="javascript:void(0);">
                        Applications
                        <i
                            class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-gray-300 dark:text-white/10 rtl:rotate-180"></i>
                    </a>
                </li>
                <li class="text-sm">
                    <a class="flex items-center text-gray-500 hover:text-primary dark:text-white/70"
                        href="javascript:void(0);">
                        Contact Inquiries
                    </a>
                </li>
            </ol>
        </div>
        <!-- End Page Header -->

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12">
                <div class="box">
                    <div class="box-header justify-between">
                        <div class="box-title">Inquiries List</div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table
                                class="table whitespace-nowrap table-bordered table-bordered-primary border-primary/10 min-w-full">
                                <thead>
                                    <tr class="border-b border-primary/10">
                                        <th scope="col" class="text-start">Name</th>
                                        <th scope="col" class="text-start">Contact</th>
                                        <th scope="col" class="text-start">Subject</th>
                                        <th scope="col" class="text-start">Message</th>
                                        <th scope="col" class="text-start">Status</th>
                                        <th scope="col" class="text-start">Date</th>
                                        <th scope="col" class="text-start">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions as $submission)
                                        <tr class="border-b border-primary/10 hover:bg-gray-50 dark:hover:bg-black/20">
                                            <td>
                                                <div class="font-semibold">{{ $submission->name }}</div>
                                            </td>
                                            <td>
                                                <div class="text-sm">{{ $submission->email }}</div>
                                                <div class="text-xs text-gray-500">{{ $submission->phone }}</div>
                                            </td>
                                            <td>{{ $submission->subject ?? 'N/A' }}</td>
                                            <td>
                                                <span class="truncate block max-w-[200px]" title="{{ $submission->message }}">
                                                    {{ Str::limit($submission->message, 50) }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.contact-submissions.update', $submission->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" onchange="this.form.submit()"
                                                        class="py-1 px-2 text-xs rounded-sm border-gray-200 focus:ring-primary focus:border-primary">
                                                        <option value="pending" {{ $submission->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="contacted" {{ $submission->status == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                                        <option value="resolved" {{ $submission->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>{{ $submission->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <form action="{{ route('admin.contact-submissions.destroy', $submission->id) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ti-btn ti-btn-sm ti-btn-danger">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-gray-500">No submissions found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $submissions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection