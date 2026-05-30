@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', 'New Student')
@section('header', 'Create Student')

@section('content')
<form method="POST" action="{{ route($routePrefix . '.students.store') }}" class="max-w-lg rounded-xl border bg-white p-6 space-y-4">
    @csrf
    <div>
        <label class="block text-xs text-gray-500 mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Phone</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded border px-3 py-2 text-sm">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Country</label>
        <input type="text" name="country" value="{{ old('country') }}" class="w-full rounded border px-3 py-2 text-sm">
    </div>
    <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Create</button>
</form>
@endsection
