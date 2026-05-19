@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">Intake Terms</h5>
                <a href="{{ route('admin.intake-terms.create') }}" class="ti-btn ti-btn-primary !m-0">
                    <i class="ri-add-line mr-1"></i> Add Intake Term
                </a>
            </div>

            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap min-w-full">
                            <thead class="bg-gray-50 dark:bg-black/20">
                                <tr class="text-left border-b border-gray-200 dark:border-white/10">
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Sort</th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Key</th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Month</th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @forelse($intakeTerms as $term)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-black/20">
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $term->sort_order }}</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200">
                                            {{ $term->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $term->key }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $term->month_num ? date('F', mktime(0, 0, 0, $term->month_num, 10)) . ' (' . $term->month_num . ')' : '-' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="badge {{ $term->is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }} rounded-full px-3 py-1 text-xs">
                                                {{ $term->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.intake-terms.edit', $term->id) }}"
                                                    class="ti-btn ti-btn-sm ti-btn-soft-primary !m-0"><i
                                                        class="ri-edit-line"></i></a>
                                                <form action="{{ route('admin.intake-terms.destroy', $term->id) }}"
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
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                            <i class="ri-calendar-event-line text-4xl text-gray-300 mb-2 block"></i>
                                            No intake terms found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200 dark:border-white/10">
                        {{ $intakeTerms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection