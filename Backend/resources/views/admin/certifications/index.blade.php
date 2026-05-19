@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">Certifications</h5>
                <a href="{{ route('admin.certifications.create') }}" class="ti-btn ti-btn-primary !m-0">
                    <i class="ri-add-line mr-1"></i> Add Certification
                </a>
            </div>
            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap min-w-full">
                            <thead class="bg-gray-50 dark:bg-black/20">
                                <tr class="text-left border-b border-gray-200 dark:border-white/10">
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Subtitle</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Link</th>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-white/10">
                                @foreach($certifications as $cert)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-black/20">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-800 dark:text-gray-200">{{ $cert->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $cert->ar_title }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $cert->subtitle }}</div>
                                            <div class="text-xs text-gray-500">{{ $cert->ar_subtitle }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-blue-500">
                                            @if($cert->certification_link) <a href="{{ $cert->certification_link }}"
                                            target="_blank">View</a> @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('admin.certifications.edit', $cert->id) }}"
                                                    class="ti-btn ti-btn-sm ti-btn-soft-primary !m-0"><i
                                                        class="ri-edit-line"></i></a>
                                                <form action="{{ route('admin.certifications.destroy', $cert->id) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Are you sure?');">
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
                    <div class="p-4 border-t border-gray-200 dark:border-white/10">{{ $certifications->links() }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection