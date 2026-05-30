@extends($routePrefix === 'team' ? 'layouts.team' : 'layouts.counsellor')

@section('title', $student->name)
@section('header', 'Student Profile')

@section('content')
<div class="max-w-4xl">
    <div class="rounded-xl border bg-white p-6 mb-6">
        <h2 class="text-lg font-semibold">{{ $student->name }}</h2>
        <p class="text-sm text-gray-500">{{ $student->email }} · {{ $student->phone ?? 'No phone' }}</p>
    </div>

    @if($showAssign ?? false)
        <form method="POST" action="{{ route('team.students.assign', $student) }}" class="mb-6 rounded-xl border bg-white p-6">
            @csrf
            <h3 class="font-semibold mb-2">Assign to counsellor</h3>
            <div class="flex gap-2">
                <select name="assigned_to" class="rounded border px-3 py-2 text-sm" required>
                    @foreach($counsellors as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                <button class="rounded bg-blue-600 px-4 py-2 text-sm text-white">Assign</button>
            </div>
        </form>
    @endif

    <div class="rounded-xl border bg-white p-6 mb-6">
        <h3 class="font-semibold mb-3">Bookings</h3>
        @if($bookings->isEmpty())
            <p class="text-sm text-gray-500">No bookings yet.</p>
        @else
            <ul class="text-sm divide-y">
                @foreach($bookings as $row)
                    @php $b = $row['model']; @endphp
                    <li class="py-2 flex justify-between">
                        <span>{{ $b->reference_no }} · {{ ucfirst($b->status) }}</span>
                        <a href="{{ route($routePrefix . '.bookings.show', ['type' => $row['type'], 'id' => $b->id]) }}" class="text-blue-600">View</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="rounded-xl border bg-white p-6">
        <h3 class="font-semibold mb-3">Quotations</h3>
        @if($quotations->isEmpty())
            <p class="text-sm text-gray-500">No quotations.</p>
        @else
            <ul class="text-sm divide-y">
                @foreach($quotations as $q)
                    <li class="py-2 flex justify-between">
                        <span>{{ $q->reference_no }} · {{ ucfirst($q->status) }}</span>
                        <a href="{{ route($routePrefix . '.quotations.show', $q) }}" class="text-blue-600">View</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
