@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">Create Certification</h5>
                <a href="{{ route('admin.certifications.index') }}" class="ti-btn ti-btn-light !m-0">Back</a>
            </div>
            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-6">
                    <form action="{{ route('admin.certifications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Title (EN)</label>
                                <input type="text" name="title" class="form-control w-full rounded-md" required>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Title (AR)</label>
                                <input type="text" name="ar_title" class="form-control w-full rounded-md">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Subtitle (EN)</label>
                                <input type="text" name="subtitle" class="form-control w-full rounded-md">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Subtitle (AR)</label>
                                <input type="text" name="ar_subtitle" class="form-control w-full rounded-md">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Image</label>
                                <input type="file" name="certificate_image" class="form-control w-full rounded-md">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Link</label>
                                <input type="url" name="certification_link" class="form-control w-full rounded-md">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="ti-btn ti-btn-primary">Create Certification</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection