@extends('admin.layouts.layout')

@section('content')
    <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
        <div class="flex flex-col gap-6">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mb-0">Create Level</h5>
                <a href="{{ route('admin.levels.index') }}" class="ti-btn ti-btn-light !m-0">
                    <i class="ri-arrow-left-line mr-1"></i> Back to List
                </a>
            </div>

            <div class="box shadow-sm border border-gray-200 dark:border-white/10 rounded-lg overflow-hidden">
                <div class="box-body !p-6">
                    <form action="{{ route('admin.levels.store') }}" method="POST">
                        @csrf
                        @include('admin.levels._form')

                        <div class="flex justify-end mt-6 gap-2">
                            <button type="submit" class="ti-btn ti-btn-primary !m-0">Create Level</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection