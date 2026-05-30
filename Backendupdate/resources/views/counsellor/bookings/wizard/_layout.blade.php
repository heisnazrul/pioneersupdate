@extends('layouts.counsellor')

@section('title', ($wizard['mode'] ?? 'booking') === 'quotation' ? 'New Quotation' : 'New Booking')
@section('header', ($wizard['mode'] ?? 'booking') === 'quotation' ? 'Create Quotation' : 'Create Booking')

@section('content')
<div class="max-w-3xl mx-auto">
    @include('counsellor.bookings.wizard._progress')

    <div class="rounded-xl border bg-white p-6 shadow-sm">
        @yield('wizard_step')
    </div>
</div>
@endsection
