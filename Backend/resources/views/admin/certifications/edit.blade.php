@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">Edit Certification</h5>
                <a href="{{ route('admin.certifications.index') }}" class="ti-btn ti-btn-light !m-0">Back</a>
            </div>
            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-6">
                    <form action="{{ route('admin.certifications.update', $certification->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Title (EN)</label>
                                <input type="text" name="title" value="{{ $certification->title }}"
                                    class="form-control w-full rounded-md" required>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Title (AR)</label>
                                <input type="text" name="ar_title" value="{{ $certification->ar_title }}"
                                    class="form-control w-full rounded-md">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Subtitle (EN)</label>
                                <input type="text" name="subtitle" value="{{ $certification->subtitle }}"
                                    class="form-control w-full rounded-md">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Subtitle (AR)</label>
                                <input type="text" name="ar_subtitle" value="{{ $certification->ar_subtitle }}"
                                    class="form-control w-full rounded-md">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Image</label>
                                <input type="file" name="certificate_image" class="form-control w-full rounded-md">
                                @if($certification->certificate_image)
                                    <div class="text-xs mt-1 text-gray-500">Current: {{ $certification->certificate_image }}
                                </div> @endif
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Link</label>
                                <input type="url" name="certification_link" value="{{ $certification->certification_link }}"
                                    class="form-control w-full rounded-md">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="ti-btn ti-btn-primary">Update Certification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection