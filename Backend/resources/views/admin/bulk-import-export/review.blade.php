@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">Bulk Import & Export / Review</p>
            <h2 class="text-2xl font-bold">{{ $resource['label'] }}</h2>
            <p class="text-sm text-gray-500 mt-1">Previewing {{ count($rows) }} of {{ $totalRows }} rows.</p>
        </div>
        <div class="space-x-2">
            <form action="{{ route('admin.bulk-ie.import-new.commit', $resourceKey) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Confirm Import</button>
            </form>
            <a href="{{ route('admin.bulk-ie.show', $resourceKey) }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary">Cancel</a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($columns as $col)
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($rows as $row)
                    <tr>
                        @foreach($columns as $col)
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $row[$col] ?? '' }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" class="px-4 py-4 text-center text-sm text-gray-500">No rows parsed.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
