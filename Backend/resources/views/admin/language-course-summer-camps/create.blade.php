@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Create Summer Camp</h2>
        <a href="{{ route('admin.language-course-summer-camps.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back</a>
    </div>
    <form action="{{ route('admin.language-course-summer-camps.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @include('admin.language-course-summer-camps._form', ['camp' => null, 'detail' => null])
        <div class="flex gap-2">
            <button class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success" type="submit">Save</button>
            <a class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary" href="{{ route('admin.language-course-summer-camps.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
