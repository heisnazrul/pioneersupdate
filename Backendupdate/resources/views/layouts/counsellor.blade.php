@extends('layouts.staff', ['panelTitle' => 'Counsellor', 'dashboardRoute' => 'counsellor.dashboard'])

@section('sidebar')
    @php $nav = fn($route) => request()->routeIs($route) ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white'; @endphp
    <a href="{{ route('counsellor.dashboard') }}" class="{{ $nav('counsellor.dashboard') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-gauge w-6 text-center mr-2"></i> Dashboard</a>
    <a href="{{ route('counsellor.bookings.create') }}" class="{{ $nav('counsellor.bookings.create') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-plus-circle w-6 text-center mr-2"></i> New Booking</a>
    <a href="{{ route('counsellor.bookings.index') }}" class="{{ $nav('counsellor.bookings.*') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-calendar-check w-6 text-center mr-2"></i> Bookings</a>
    <a href="{{ route('counsellor.students.index') }}" class="{{ $nav('counsellor.students.*') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-user-graduate w-6 text-center mr-2"></i> Students</a>
    <a href="{{ route('counsellor.quotations.index') }}" class="{{ $nav('counsellor.quotations.*') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-file-invoice w-6 text-center mr-2"></i> Quotations</a>
    <a href="{{ route('counsellor.profile.edit') }}" class="{{ $nav('counsellor.profile.*') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-user w-6 text-center mr-2"></i> Profile</a>
@endsection
