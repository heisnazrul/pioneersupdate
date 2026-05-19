@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">CMS / {{ ucfirst($app) }}</p>
            <h2 class="text-2xl font-bold">New Page</h2>
        </div>
        <a href="{{ route($app === 'courseenglish' ? 'admin.cms.course-english' : 'admin.cms.university') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary">Back</a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.cms.store', $app) }}" method="POST" class="space-y-4">
            @include('admin.cms.form', ['page' => $page])
            <div class="flex gap-2">
                <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Save</button>
                <a href="{{ route($app === 'courseenglish' ? 'admin.cms.course-english' : 'admin.cms.university') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
