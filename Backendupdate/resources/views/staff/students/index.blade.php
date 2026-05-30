@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', 'Students')
@section('header', 'Students')

@section('content')
<div class="mb-4 flex justify-between items-center">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search }}" class="rounded border px-3 py-2 text-sm" placeholder="Search name or email">
        @if($showAssign ?? false)
            <select name="counsellor_id" class="rounded border px-3 py-2 text-sm">
                <option value="">All counsellors</option>
                @foreach($counsellors as $c)
                    <option value="{{ $c->id }}" @selected(($activeCounsellor ?? '') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        @endif
        <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Search</button>
    </form>
    <a href="{{ route($routePrefix . '.students.create') }}" class="rounded bg-blue-600 px-4 py-2 text-sm text-white">New student</a>
</div>

<div class="overflow-x-auto rounded-xl border bg-white">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Phone</th><th class="px-4 py-3"></th></tr>
        </thead>
        <tbody class="divide-y">
            @forelse($students as $student)
                <tr>
                    <td class="px-4 py-3">{{ $student->name }}</td>
                    <td class="px-4 py-3">{{ $student->email }}</td>
                    <td class="px-4 py-3">{{ $student->phone ?? '—' }}</td>
                    <td class="px-4 py-3 text-right"><a href="{{ route($routePrefix . '.students.show', $student) }}" class="text-blue-600">View</a></td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $students->links() }}</div>
@endsection
