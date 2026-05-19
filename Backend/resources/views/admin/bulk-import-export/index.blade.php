@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <h2 class="text-2xl font-bold">Bulk Import & Export</h2>
        <p class="text-sm text-gray-500">Choose a table to export or prepare imports.</p>
    </div>

    @foreach($groups as $group)
        <div class="mb-6">
            <div class="flex items-center mb-2">
                <h3 class="text-lg font-semibold text-gray-800">{{ $group['label'] }}</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach($group['items'] as $item)
                    <a href="{{ route('admin.bulk-ie.show', $item['key']) }}" class="block bg-white shadow rounded-lg p-5 border hover:border-primary transition">
                        <div class="text-lg font-semibold text-gray-800">{{ $item['label'] }}</div>
                        <div class="text-sm text-gray-500 mt-1">Rows: {{ number_format($item['count']) }}</div>
                        <div class="mt-3 text-primary text-sm font-medium">Manage</div>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</div>
@endsection
