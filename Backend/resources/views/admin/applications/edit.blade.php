@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex justify-center">
            <div class="col-span-12 md:col-span-8 lg:col-span-6 xl:col-span-5 max-w-2xl w-full">
                <div class="box mt-10">
                    <div class="box-header justify-between">
                        <div class="box-title">
                            Edit Application #{{ $application->application_id }}
                        </div>
                        <a href="{{ route('admin.applications.show', $application->id) }}"
                            class="text-xs text-primary hover:underline">
                            View Details
                        </a>
                    </div>
                    <div class="box-body">
                        <form action="{{ route('admin.applications.update', $application->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 gap-4">
                                <!-- Status -->
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0 font-bold">Application Status</label>
                                    <select name="status" class="ti-form-select rounded-sm">
                                        @foreach(['draft','pending','submitted','reviewing','contacted','accepted','rejected','invalid'] as $status)
                                            <option value="{{ $status }}" {{ $application->status == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Assignee -->
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0 font-bold">Assign To Staff</label>
                                    <select name="assigned_to" class="ti-form-select rounded-sm">
                                        <option value="">-- Unassigned --</option>
                                        @foreach($staff as $user)
                                            <option value="{{ $user->id }}" {{ $application->assigned_to == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ ucfirst($user->role) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Notes -->
                                <div class="space-y-2">
                                    <label class="ti-form-label mb-0 font-bold">Internal Notes</label>
                                    <textarea name="status_notes" rows="6" class="ti-form-input rounded-sm"
                                        placeholder="Add notes useful for other team members...">{{ $application->status_notes }}</textarea>
                                    <span class="text-xs text-gray-400">These notes are only visible to admins/staff.</span>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-white/10">
                                    <a href="{{ route('admin.applications.index') }}" class="ti-btn ti-btn-soft-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="ti-btn ti-btn-primary">
                                        <i class="ti ti-device-floppy"></i> Update Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
