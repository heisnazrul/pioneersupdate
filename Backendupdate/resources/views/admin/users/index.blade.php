@extends('layouts.admin')

@section('title', 'Users Management')
@section('header', 'Users')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 rounded-lg {{ !$activeRole ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }} text-sm font-medium transition-colors">
            All ({{ $totalUsers }})
        </a>
        @foreach($roles as $role)
            <a href="{{ route('admin.users.role', $role) }}" class="px-4 py-2 rounded-lg {{ $activeRole == $role ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700' }} text-sm font-medium transition-colors">
                {{ ucfirst($role) }} ({{ $roleCounts[$role] ?? 0 }})
            </a>
        @endforeach
    </div>
    <a href="{{ route('admin.users.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add User
    </a>
</div>

<div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-750 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">
                <th class="px-6 py-4 font-semibold">User</th>
                <th class="px-6 py-4 font-semibold">Role</th>
                <th class="px-6 py-4 font-semibold">Status</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold mr-3 overflow-hidden border border-gray-200 dark:border-gray-700">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="" class="w-full h-full object-cover">
                            @else
                                {{ substr($user->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <form action="{{ route('admin.users.status', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="text-xs font-medium rounded-full px-3 py-1 border-0 focus:ring-2 focus:ring-primary-500 {{ $user->status == 'active' ? 'bg-green-100 text-green-800' : ($user->status == 'banned' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ $user->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </form>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors p-1">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors p-1">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-10 text-center text-gray-500">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700">
        {{ $users->links() }}
    </div>
</div>
@endsection
