@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex justify-between py-10">
            <h2 class="text-2xl font-bold mb-4">Edit Category</h2>
            <a href="{{ route('admin.categories.index') }}"
                class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Back to List</a>
        </div>

        @if ($errors->any())
            <div class="mb-4 text-red-700 bg-red-50 border border-red-200 rounded-md px-4 py-2">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.categories._form', ['category' => $category])
        </form>
    </div>
@endsection