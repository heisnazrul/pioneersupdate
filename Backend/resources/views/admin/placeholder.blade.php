@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center p-5">
                    <i class="ri-hammer-line ri-4x text-muted mb-3"></i>
                    <h3 class="card-title">{{ $title ?? 'Coming Soon' }}</h3>
                    <p class="card-text text-muted">Thinking about migrating this feature? It's on the roadmap!</p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary mt-3">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection