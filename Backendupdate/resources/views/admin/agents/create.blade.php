@extends('layouts.admin')

@section('title', 'Add Agent')
@section('header', 'New Affiliate Agent')

@section('content')
<div class="max-w-4xl">
    @include('admin.affiliates._nav')

    <div class="mb-6">
        <a href="{{ route('admin.agents.index') }}" class="flex items-center gap-1 text-sm text-gray-500 hover:text-primary-600">
            <i class="fa-solid fa-arrow-left"></i> Back to Agents
        </a>
    </div>

    @if($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.agents.store') }}" method="POST" class="space-y-6">
        @csrf
        @include('admin.agents._form', ['agent' => null, 'userStatuses' => $userStatuses])
        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-primary-600 px-8 py-3 font-semibold text-white shadow-lg hover:bg-primary-700">Create Agent</button>
        </div>
    </form>
</div>
@endsection
