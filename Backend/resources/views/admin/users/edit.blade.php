@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <div>
            <h2 class="text-2xl font-bold mb-2">Edit User</h2>
            <p class="text-sm text-gray-500">Update account details and adjust access as needed.</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Back to Users
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.users._form', ['user' => $user, 'roles' => $roles, 'statuses' => $statuses])
    </form>
</div>
@endsection
