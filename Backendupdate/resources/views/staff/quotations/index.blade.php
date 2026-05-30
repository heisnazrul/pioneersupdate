@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', 'Quotations')
@section('header', 'Quotations')

@section('content')
<div class="mb-4 flex justify-between">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ $search }}" class="rounded border px-3 py-2 text-sm" placeholder="Search">
        <select name="status" class="rounded border px-3 py-2 text-sm">
            <option value="">All statuses</option>
            @foreach($statuses as $status)
                <option value="{{ $status }}" @selected($activeStatus === $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @if($showCounsellorFilter ?? false)
            <select name="counsellor_id" class="rounded border px-3 py-2 text-sm">
                <option value="">All counsellors</option>
                @foreach($counsellors ?? [] as $c)
                    <option value="{{ $c->id }}" @selected(($activeCounsellor ?? '') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        @endif
        <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Filter</button>
    </form>
    @if($routePrefix === 'counsellor')
        <a href="{{ route('counsellor.bookings.create', ['mode' => 'quotation']) }}" class="rounded bg-blue-600 px-4 py-2 text-sm text-white">New booking / quote</a>
    @else
        <a href="{{ route($routePrefix . '.quotations.create') }}" class="rounded bg-blue-600 px-4 py-2 text-sm text-white">New quotation</a>
    @endif
</div>

<div class="overflow-x-auto rounded-xl border bg-white">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Reference</th>
                <th class="px-4 py-3">Student</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($quotations as $quotation)
                <tr>
                    <td class="px-4 py-3 font-mono">{{ $quotation->reference_no }}</td>
                    <td class="px-4 py-3">{{ $quotation->student?->name }}</td>
                    <td class="px-4 py-3">{{ ucfirst($quotation->status) }}</td>
                    <td class="px-4 py-3">{{ number_format((float) $quotation->total_amount, 2) }} {{ $quotation->display_currency }}</td>
                    <td class="px-4 py-3 text-right"><a href="{{ route($routePrefix . '.quotations.show', $quotation) }}" class="text-blue-600">View</a></td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">No quotations.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $quotations->links() }}</div>
@endsection
