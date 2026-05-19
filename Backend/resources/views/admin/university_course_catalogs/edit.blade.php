@extends('admin.layouts.layout')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-semibold">Edit Course Catalog</h2>
        <a href="{{ route('admin.university-course-catalogs.index') }}" class="ti-btn ti-btn-light !m-0">Back to List</a>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.university-course-catalogs.update', $universityCourseCatalog) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            @include('admin.university_course_catalogs._form')

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.university-course-catalogs.index') }}" class="ti-btn ti-btn-light">Cancel</a>
                <button type="submit" class="ti-btn ti-btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection
