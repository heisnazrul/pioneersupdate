@php
    $isEdit = isset($user);
    $existingAvatar = $user->avatar ?? null;
    $avatarUrl = $existingAvatar
        ? asset('storage/'.ltrim($existingAvatar, '/'))
        : asset('assets/male.png');
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Profile Avatar</label>
        <div class="mt-2 flex items-center gap-4">
            <img src="{{ $avatarUrl }}" alt="Current avatar" class="h-16 w-16 rounded-full object-cover border">
            <div class="space-y-2">
                <input
                    type="file"
                    name="avatar"
                    accept="image/*"
                    class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-primary/10 file:px-4 file:py-2 file:text-primary"
                >
                <p class="text-xs text-gray-500">PNG or JPG up to 5&nbsp;MB.</p>
                @if($isEdit && $existingAvatar)
                    <label class="inline-flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remove_avatar" value="1" class="rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="ml-2">Remove current avatar</span>
                    </label>
                @endif
            </div>
        </div>
    </div>

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $user->name ?? '') }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
        >
    </div>

    <div>
        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
        <input
            type="text"
            id="username"
            name="username"
            value="{{ old('username', $user->username ?? '') }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Leave empty to auto-generate"
        >
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email', $user->email ?? '') }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
        >
    </div>

    <div>
        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
        <select
            id="role"
            name="role"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
        >
            @foreach($roles as $roleOption)
                <option value="{{ $roleOption }}" {{ old('role', $user->role ?? '') === $roleOption ? 'selected' : '' }}>
                    {{ ucfirst($roleOption) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
        <select
            id="status"
            name="status"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            required
        >
            @foreach($statuses as $statusOption)
                <option value="{{ $statusOption }}" {{ old('status', $user->status ?? 'active') === $statusOption ? 'selected' : '' }}>
                    {{ ucfirst($statusOption) }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
        <input
            type="text"
            id="phone"
            name="phone"
            value="{{ old('phone', $user->phone ?? '') }}"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
        >
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">
            Password {{ $isEdit ? '(leave blank to keep current password)' : '' }}
        </label>
        <input
            type="password"
            id="password"
            name="password"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            {{ $isEdit ? '' : 'required' }}
        >
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            {{ $isEdit ? '' : 'required' }}
        >
    </div>
</div>

<div class="flex space-x-2 mt-8">
    <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">
        {{ $isEdit ? 'Update User' : 'Create User' }}
    </button>
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100">
        Cancel
    </a>
</div>
