@extends('admin.layouts.layout')

@section('content')
@php
    $appTimezone = config('app.timezone', 'UTC');
@endphp

<div class="main-content py-10">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-10">
        <div>
            <h2 class="text-2xl font-bold mb-2">Manage Users</h2>
            <p class="text-sm text-gray-500">Review all accounts or focus on a specific role to take action.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
            Create User
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex flex-wrap gap-2 mb-6">
        <a
            href="{{ route('admin.users.index') }}"
            class="px-4 py-2 rounded-full text-sm border {{ $activeRole ? 'border-gray-200 text-gray-600 hover:bg-gray-50' : 'border-green-500 bg-green-100 text-green-700' }}"
        >
            All Users ({{ $totalUsers }})
        </a>
        @foreach ($roles as $roleOption)
            <a
                href="{{ route('admin.users.role', $roleOption) }}"
                class="px-4 py-2 rounded-full text-sm border {{ $activeRole === $roleOption ? 'border-green-500 bg-green-100 text-green-700' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}"
            >
                {{ ucfirst($roleOption) }} ({{ $roleCounts[$roleOption] ?? 0 }})
            </a>
        @endforeach
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full bg-white">
            <thead>
                <tr class="border-b">
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">User</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Phone</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Role</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Last Login</th>
                    <th class="px-6 py-3 text-right text-sm font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            @php
                                $avatar = $user->avatar
                                    ? asset('storage/'.ltrim($user->avatar, '/'))
                                    : asset('assets/male.png');
                            @endphp
                            <div class="flex items-center gap-3">
                                <img src="{{ $avatar }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover border">
                                <div class="flex flex-col">
                                    <span>{{ $user->name }}</span>
                                    <span class="text-xs text-gray-500">{{ '@' . $user->username }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $user->phone ?: '—' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ ucfirst($user->role) }}</td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-700 border border-green-200',
                                    'inactive' => 'bg-yellow-100 text-yellow-700 border border-yellow-200',
                                    'banned' => 'bg-red-100 text-red-700 border border-red-200',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$user->status] ?? 'bg-gray-100 text-gray-700 border border-gray-200' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        @php
                            $lastLoginLabel = $user->last_login_at
                                ? $user->last_login_at->clone()->timezone($appTimezone)->format('M d, Y H:i')
                                : 'Never';
                        @endphp
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $lastLoginLabel }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800">Edit</a>

                            @if (auth()->id() !== $user->id)
                                @php
                                    $toggleLabel = $user->status === 'banned' ? 'Activate' : 'Ban';
                                    $nextStatus = $user->status === 'banned' ? 'active' : 'banned';
                                    $toggleClasses = $user->status === 'banned'
                                        ? 'text-green-600 hover:text-green-800'
                                        : 'text-orange-600 hover:text-orange-800';
                                @endphp
                                <form action="{{ route('admin.users.status', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                                    <button type="submit" class="{{ $toggleClasses }}">
                                        {{ $toggleLabel }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                            No users found for this filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
