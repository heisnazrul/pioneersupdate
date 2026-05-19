@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-6">
        <h2 class="text-2xl font-bold">Create Summer Camp Detail</h2>
        <a href="{{ route('admin.language-course-summer-camp-details.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back</a>
    </div>

    @if($camps->isEmpty())
        <div class="mb-4 rounded border border-yellow-300 bg-yellow-50 px-4 py-3 text-sm text-yellow-800">
            All summer camps already have details. Edit an existing detail from the list.
        </div>
    @endif

    <form action="{{ route('admin.language-course-summer-camp-details.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @include('admin.language-course-summer-camp-details._form', ['detail' => null, 'camps' => $camps])
        <div class="flex gap-2">
            <button class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success" type="submit" {{ $camps->isEmpty() ? 'disabled' : '' }}>Save</button>
            <a class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary" href="{{ route('admin.language-course-summer-camp-details.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
