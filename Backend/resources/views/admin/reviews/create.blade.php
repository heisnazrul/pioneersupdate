@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">Create Review</h5>
                <a href="{{ route('admin.reviews.index') }}" class="ti-btn ti-btn-light !m-0">Back</a>
            </div>
            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-6">
                    <form action="{{ route('admin.reviews.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-12 gap-6">
                            <!-- Names -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Name (EN)</label>
                                <input type="text" name="name" class="form-control w-full rounded-md" required>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Name (AR)</label>
                                <input type="text" name="ar_name" class="form-control w-full rounded-md">
                            </div>

                            <!-- University / Course / Country -->
                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-sm font-medium mb-2">University Name</label>
                                <input type="text" name="university_name" class="form-control w-full rounded-md"
                                    placeholder="e.g. Oxford University">
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-sm font-medium mb-2">Course Name</label>
                                <input type="text" name="course_name" class="form-control w-full rounded-md"
                                    placeholder="e.g. MBA">
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-sm font-medium mb-2">Country Name</label>
                                <input type="text" name="country_name" class="form-control w-full rounded-md"
                                    placeholder="e.g. UK">
                            </div>

                            <!-- Rating & Other -->
                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-sm font-medium mb-2">Rating (1-5)</label>
                                <input type="number" name="rating" min="1" max="5" value="5"
                                    class="form-control w-full rounded-md" required>
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="block text-sm font-medium mb-2">Active</label>
                                <select name="is_active" class="form-control w-full rounded-md">
                                    <option value="1" selected>Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>

                            <!-- Media -->
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Student Photo</label>
                                <input type="file" name="photo" class="form-control w-full rounded-md">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Video Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control w-full rounded-md">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Video URL (mp4/hosted)</label>
                                <input type="url" name="video_url" class="form-control w-full rounded-md"
                                    placeholder="https://...">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Video Iframe (YouTube Embed)</label>
                                <textarea name="video_iframe" rows="1" class="form-control w-full rounded-md"
                                    placeholder="<iframe...></iframe>"></textarea>
                            </div>

                            <!-- Text -->
                            <div class="col-span-12">
                                <label class="block text-sm font-medium mb-2">Review Text</label>
                                <textarea name="review_text" rows="4" class="form-control w-full rounded-md"></textarea>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="ti-btn ti-btn-primary">Create Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection