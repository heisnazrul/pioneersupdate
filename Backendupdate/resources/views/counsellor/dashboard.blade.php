@extends('layouts.counsellor')

@section('title', 'Dashboard')
@section('header', 'Dashboard')

@section('content')
<div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4 mb-8">
    @foreach($statuses as $status)
        <div class="rounded-xl border bg-white p-4">
            <p class="text-xs uppercase text-gray-500">{{ ucfirst($status) }}</p>
            <p class="text-2xl font-semibold">{{ $statusCounts[$status] ?? 0 }}</p>
        </div>
    @endforeach
</div>

<div class="grid gap-6 md:grid-cols-3 mb-8">
    <div class="rounded-xl border bg-white p-5">
        <p class="text-sm text-gray-500">Awaiting payment (confirmed)</p>
        <p class="text-3xl font-bold text-amber-600">{{ $pendingPayments }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5">
        <p class="text-sm text-gray-500">My students</p>
        <p class="text-3xl font-bold">{{ $studentCount }}</p>
    </div>
    <div class="rounded-xl border bg-white p-5">
        <p class="text-sm text-gray-500">Open quotations</p>
        <p class="text-3xl font-bold">{{ $openQuotations }}</p>
    </div>
</div>

<div class="rounded-xl border bg-white p-6">
    <h2 class="font-semibold mb-4">Recent payment activity</h2>
    @if($recentEvents->isEmpty())
        <p class="text-sm text-gray-500">No recent activity.</p>
    @else
        <ul class="divide-y text-sm">
            @foreach($recentEvents as $event)
                <li class="py-2 flex justify-between">
                    <span>{{ $event->user?->name }} · {{ $event->old_status }} → {{ $event->new_status }}</span>
                    <span class="text-gray-500">{{ $event->created_at?->diffForHumans() }}</span>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
