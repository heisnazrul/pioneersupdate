@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', $quotation->reference_no)
@section('header', 'Quotation')

@section('content')
<div class="max-w-3xl">
    <div class="mb-4 flex flex-wrap gap-2 justify-between">
        <div>
            <p class="font-mono text-lg">{{ $quotation->reference_no }}</p>
            <p class="text-sm text-gray-500">{{ ucfirst($quotation->status) }} · {{ $quotation->student?->name }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route($routePrefix . '.quotations.pdf', $quotation) }}" class="rounded border px-3 py-2 text-sm">Download PDF</a>
            @if($quotation->status === 'draft')
                <a href="{{ route($routePrefix . '.quotations.edit', $quotation) }}" class="rounded border px-3 py-2 text-sm">Edit</a>
            @endif
        </div>
    </div>

    <div class="rounded-xl border bg-white p-6 mb-6 text-sm space-y-2">
        <p><span class="text-gray-500">Type:</span> {{ str_replace('_', ' ', $quotation->booking_type) }}</p>
        <p><span class="text-gray-500">School:</span> {{ $quotation->school?->name_en ?? '—' }}</p>
        <p><span class="text-gray-500">Total:</span> <strong>{{ number_format((float) $quotation->total_amount, 2) }} {{ $quotation->display_currency }}</strong></p>
        <p><span class="text-gray-500">Valid until:</span> {{ optional($quotation->valid_until)->format('Y-m-d') ?? '—' }}</p>
        @if($quotation->notes)
            <p><span class="text-gray-500">Notes:</span> {{ $quotation->notes }}</p>
        @endif
    </div>

    @if($showAssign ?? false)
        <form method="POST" action="{{ route('team.quotations.assign', $quotation) }}" class="mb-6 rounded-xl border bg-white p-6">
            @csrf
            <h3 class="font-semibold mb-2">Assign counsellor</h3>
            <div class="flex gap-2">
                <select name="assigned_to" class="rounded border px-3 py-2 text-sm">
                    <option value="">Unassigned</option>
                    @foreach($counsellors as $c)
                        <option value="{{ $c->id }}" @selected($quotation->assigned_to == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
                <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Save</button>
            </div>
        </form>
    @endif

    <div class="flex flex-wrap gap-2">
        @if(in_array($quotation->status, ['draft', 'sent', 'accepted']))
            <form method="POST" action="{{ route($routePrefix . '.quotations.send', $quotation) }}">
                @csrf
                <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Mark as sent</button>
            </form>
        @endif
        @if(in_array($quotation->status, ['draft', 'sent', 'accepted']) && !$quotation->converted_booking_id)
            <form method="POST" action="{{ route($routePrefix . '.quotations.convert', $quotation) }}" onsubmit="return confirm('Convert to booking?')">
                @csrf
                <button class="rounded bg-green-600 px-4 py-2 text-sm text-white">Convert to booking</button>
            </form>
        @endif
    </div>
</div>
@endsection
