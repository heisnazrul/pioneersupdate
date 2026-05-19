@extends('admin.layouts.layout')

@section('content')
<div class="main-content py-10">
    <div class="flex justify-between items-center py-6">
        <div>
            <p class="text-sm text-gray-500">Bulk Import & Export</p>
            <h2 class="text-2xl font-bold">{{ $resource['label'] }}</h2>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.bulk-ie.export-blank', $resourceKey) }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-secondary">Export Blank</a>
            <a href="{{ route('admin.bulk-ie.export-all', $resourceKey) }}" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-primary">Export All</a>
            <form action="{{ route('admin.bulk-ie.import-new', $resourceKey) }}" method="POST" enctype="multipart/form-data" class="inline-flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept=".csv,text/csv" class="text-sm" required>
                <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-success">Import New</button>
            </form>
            <form action="{{ route('admin.bulk-ie.import-update', $resourceKey) }}" method="POST" enctype="multipart/form-data" class="inline-flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept=".csv,text/csv" class="text-sm" required>
                <button type="submit" class="ti-btn rounded-full ti-btn-outline ti-btn-outline-warning">Import Updates</button>
            </form>
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
                            <td class="px-4 py-2 text-sm text-gray-700">{{ data_get($row, $col) }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" class="px-4 py-4 text-center text-sm text-gray-500">No rows found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $rows->links() }}
    </div>
</div>
@endsection
