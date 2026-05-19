@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">University Campuses</h5>
                <a href="{{ route('admin.campuses.create') }}" class="ti-btn ti-btn-primary !m-0">
                    <i class="ri-add-line mr-1"></i> Add Campus
                </a>
            </div>

            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap min-w-full">
                            <thead class="bg-gray-50 dark:bg-black/20">
                                <tr class="text-left border-b border-gray-200 dark:border-white/10">
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">University
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Campus
                                        Name</th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Location
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @forelse($campuses as $campus)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-black/20">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $campus->university->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $campus->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            @if($campus->is_online)
                                                <span class="text-xs text-gray-400">N/A (Online)</span>
                                            @else
                                                {{ $campus->city->name ?? 'Unknown City' }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($campus->is_online)
                                                <span
                                                    class="badge bg-info/10 text-info rounded-full px-3 py-1 text-xs">Online</span>
                                            @else
                                                <span
                                                    class="badge bg-secondary/10 text-secondary rounded-full px-3 py-1 text-xs">On-Campus</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="badge {{ $campus->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} rounded-full px-3 py-1 text-xs">
                                                {{ $campus->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.campuses.edit', $campus->id) }}"
                                                    class="ti-btn ti-btn-sm ti-btn-soft-primary !m-0"><i
                                                        class="ri-edit-line"></i></a>
                                                <form action="{{ route('admin.campuses.destroy', $campus->id) }}" method="POST"
                                                    class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="ti-btn ti-btn-sm ti-btn-soft-danger !m-0"><i
                                                            class="ri-delete-bin-line"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <i class="ri-building-4-line text-4xl text-gray-300 mb-2 block"></i>
                                            No campuses found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200 dark:border-white/10">
                        {{ $campuses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection