@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">FAQs</h5>
                <a href="{{ route('admin.faqs.create') }}" class="ti-btn ti-btn-primary !m-0">
                    <i class="ri-add-line mr-1"></i> Add FAQ
                </a>
            </div>
            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap min-w-full">
                            <thead class="bg-gray-50 dark:bg-black/20">
                                <tr class="text-left border-b border-gray-200 dark:border-white/10">
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Question (EN)</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Category</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @foreach($faqs as $faq)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-black/20">
                                        <td
                                            class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-gray-200 text-wrap w-1/2">
                                            {{ Str::limit($faq->question, 80) }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $faq->category }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.faqs.edit', $faq->id) }}"
                                                    class="ti-btn ti-btn-sm ti-btn-soft-primary !m-0"><i
                                                        class="ri-edit-line"></i></a>
                                                <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST"
                                                    class="inline-block" onsubmit="return confirm('Are you sure?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="ti-btn ti-btn-sm ti-btn-soft-danger !m-0"><i
                                                            class="ri-delete-bin-line"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-200 dark:border-white/10">{{ $faqs->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection