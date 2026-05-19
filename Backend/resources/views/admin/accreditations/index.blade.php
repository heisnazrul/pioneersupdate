@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between py-10">
        <h2 class="text-2xl font-bold mb-4">Accreditations List</h2>
        <a href="{{ route('admin.accreditations.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">
            Create Accreditation
        </a>
        <ol class="flex items-center whitespace-nowrap min-w-0 pb-2">
            <li class="text-sm">
                    <a class="flex items-center text-primary hover:text-primary dark:text-primary" href="javascript:void(0);">
                        <svg class="flex-shrink-0 ltr:mr-3 rtl:ml-3 h-4 w-4 text-primary hover:text-primary dark:text-primary" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"></path>
                            <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"></path>
                        </svg>
                        Home
                        <svg class="flex-shrink-0 mx-3 overflow-visible h-2.5 w-2.5 text-gray-300 dark:text-white/10 rtl:rotate-180" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 1L10.6869 7.16086C10.8637 7.35239 10.8637 7.64761 10.6869 7.83914L5 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"></path>
                        </svg>
                    </a>
            </li>
            <li class="text-sm">
                <a class="flex items-center text-primary hover:text-primary dark:text-primary" href="javascript:void(0);">
                    <span class="flex items-center text-gray-500 dark:text-white/70">Accreditations</span>
                </a>
            </li>
        </ol>
    </div>

    <!-- Table of Accreditations -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="border-b">
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">ID</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">Arabic Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">Picture</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-white/70">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accreditations as $accreditation)
                <tr class="border-b">
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $accreditation->id }}</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $accreditation->name }}</td>
                    <td class="px-6 py-4 text-sm">{{ $accreditation->ar_name }}</td>
                    <td class="px-6 py-4 text-sm">
                        @if($accreditation->picture)
                           <img src="{{ asset('storage/'.ltrim($accreditation->picture, '/')) }}" width="50" height="50" alt="Accreditation Picture" class="rounded border object-cover">
                        @else
                            No Image
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <a href="{{ route('admin.accreditations.edit', $accreditation) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                        <form action="{{ route('admin.accreditations.destroy', $accreditation) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 ml-4">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4">
            {{ $accreditations->links() }}
        </div>
    </div>
</div>
@endsection
