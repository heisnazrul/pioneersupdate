@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', 'Profile')
@section('header', 'My Profile')

@section('content')
<form method="POST" action="{{ route($routePrefix . '.profile.update') }}" class="max-w-lg rounded-xl border bg-white p-6 space-y-4">
    @csrf
    @method('PUT')
    <div>
        <label class="block text-xs text-gray-500 mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">New password</label>
        <input type="password" name="password" class="w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Confirm password</label>
        <input type="password" name="password_confirmation" class="w-full rounded border px-3 py-2 text-sm">
    </div>
    <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Save</button>
</form>
@endsection
