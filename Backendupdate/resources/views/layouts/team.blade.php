@extends('layouts.staff', ['panelTitle' => 'Team Ops', 'dashboardRoute' => 'team.dashboard'])

@section('sidebar')
    @php $nav = fn($route) => request()->routeIs($route) ? 'bg-blue-600 text-white' : 'hover:bg-slate-800 hover:text-white'; @endphp
    <a href="{{ route('team.dashboard') }}" class="{{ $nav('team.dashboard') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-gauge w-6 text-center mr-2"></i> Dashboard</a>
    <a href="{{ route('team.bookings.index') }}" class="{{ $nav('team.bookings.*') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-calendar-check w-6 text-center mr-2"></i> Bookings</a>
    <a href="{{ route('team.students.index') }}" class="{{ $nav('team.students.*') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-user-graduate w-6 text-center mr-2"></i> Students</a>
    <a href="{{ route('team.quotations.index') }}" class="{{ $nav('team.quotations.*') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-file-invoice w-6 text-center mr-2"></i> Quotations</a>
    <a href="{{ route('team.profile.edit') }}" class="{{ $nav('team.profile.*') }} flex items-center px-3 py-2 rounded-md"><i class="fa-solid fa-user w-6 text-center mr-2"></i> Profile</a>
@endsection
