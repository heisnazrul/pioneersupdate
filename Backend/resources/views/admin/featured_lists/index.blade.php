@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">Featured Lists</h5>
                <a href="{{ route('admin.featured-lists.create') }}" class="ti-btn ti-btn-primary !m-0">
                    <i class="ri-add-line mr-1"></i> Add Featured List
                </a>
            </div>

            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap min-w-full">
                            <thead class="bg-gray-50 dark:bg-black/20">
                                <tr class="text-left border-b border-gray-200 dark:border-white/10">
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Key</th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @forelse($lists as $list)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-black/20">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $list->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $list->key }}</td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="badge {{ $list->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} rounded-full px-3 py-1 text-xs">
                                                {{ $list->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.featured-lists.edit', $list->id) }}"
                                                    class="ti-btn ti-btn-sm ti-btn-soft-primary !m-0"><i
                                                        class="ri-edit-line"></i></a>
                                                <form action="{{ route('admin.featured-lists.destroy', $list->id) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Are you sure?');">
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
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                            <i class="ri-list-check-2 text-4xl text-gray-300 mb-2 block"></i>
                                            No featured lists found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200 dark:border-white/10">
                        {{ $lists->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
