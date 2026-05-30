@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', 'Bookings')
@section('header', 'Bookings')

@section('content')
<div class="mb-4 flex flex-wrap justify-between items-end gap-2">
<form method="GET" class="flex flex-wrap gap-2 items-end">
    <div>
        <label class="block text-xs text-gray-500">Search</label>
        <input type="text" name="search" value="{{ $search }}" class="rounded border px-3 py-2 text-sm" placeholder="Ref, name, email">
    </div>
    <div>
        <label class="block text-xs text-gray-500">Type</label>
        <select name="type" class="rounded border px-3 py-2 text-sm">
            <option value="all" @selected($activeType === 'all')>All</option>
            <option value="language" @selected($activeType === 'language')>Language</option>
            <option value="online" @selected($activeType === 'online')>Online</option>
        </select>
    </div>
    <div>
        <label class="block text-xs text-gray-500">Status</label>
        <select name="status" class="rounded border px-3 py-2 text-sm">
            <option value="">All</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected($activeStatus === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
    </div>
    @if($showCounsellorFilter ?? false)
        <div>
            <label class="block text-xs text-gray-500">Counsellor</label>
            <select name="counsellor_id" class="rounded border px-3 py-2 text-sm">
                <option value="">All</option>
                @foreach($counsellors as $c)
                    <option value="{{ $c->id }}" @selected(($activeCounsellor ?? '') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Filter</button>
</form>
@if($routePrefix === 'counsellor')
    <a href="{{ route('counsellor.bookings.create') }}" class="rounded bg-blue-600 px-4 py-2 text-sm text-white whitespace-nowrap">New booking</a>
@endif
</div>

<div class="overflow-x-auto rounded-xl border bg-white">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Reference</th>
                <th class="px-4 py-3">Type</th>
                <th class="px-4 py-3">Student</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Total</th>
                @if($showCounsellorFilter ?? false)<th class="px-4 py-3">Counsellor</th>@endif
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($bookings as $row)
                <tr>
                    <td class="px-4 py-3 font-mono">{{ $row['reference_no'] }}</td>
                    <td class="px-4 py-3">{{ str_replace('_', ' ', $row['type']) }}</td>
                    <td class="px-4 py-3">{{ $row['contact_name'] }}<br><span class="text-gray-500">{{ $row['contact_email'] }}</span></td>
                    <td class="px-4 py-3"><span class="rounded bg-gray-100 px-2 py-1 text-xs">{{ ucfirst($row['status']) }}</span></td>
                    <td class="px-4 py-3">{{ number_format($row['total_amount'], 2) }} {{ $row['display_currency'] }}</td>
                    @if($showCounsellorFilter ?? false)<td class="px-4 py-3">{{ $row['assignee_name'] ?? '—' }}</td>@endif
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route($routePrefix . '.bookings.show', ['type' => $row['type'], 'id' => $row['id']]) }}" class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No bookings found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>
@endsection
