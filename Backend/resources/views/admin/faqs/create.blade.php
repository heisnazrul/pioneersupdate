@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">Create FAQ</h5>
                <a href="{{ route('admin.faqs.index') }}" class="ti-btn ti-btn-light !m-0">Back</a>
            </div>
            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-6">
                    <form action="{{ route('admin.faqs.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Category (EN)</label>
                                <input type="text" name="category" class="form-control w-full rounded-md" required>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Category (AR)</label>
                                <input type="text" name="ar_category" class="form-control w-full rounded-md">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Question (EN)</label>
                                <input type="text" name="question" class="form-control w-full rounded-md" required>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Question (AR)</label>
                                <input type="text" name="ar_question" class="form-control w-full rounded-md">
                            </div>

                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Answer (EN)</label>
                                <textarea name="answer" rows="5" class="form-control w-full rounded-md" required></textarea>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="block text-sm font-medium mb-2">Answer (AR)</label>
                                <textarea name="ar_answer" rows="5" class="form-control w-full rounded-md"></textarea>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="ti-btn ti-btn-primary">Create FAQ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection