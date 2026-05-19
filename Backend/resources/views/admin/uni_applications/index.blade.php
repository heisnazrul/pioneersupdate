@extends('admin.layouts.layout')

@section('content')
    <div class="main-content py-10">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 py-10">
            <div>
                <h2 class="text-2xl font-bold mb-2">Uni Applications</h2>
                <p class="text-sm text-gray-500">Manage university course inquiries and applications.</p>
            </div>
        </div>

        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="border-b">
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">ID</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Applicant</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Course</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Intake</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applications as $app)
                        <tr class="border-b">
                            <td class="px-6 py-4 text-sm text-gray-700">#{{ $app->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $app->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <div class="flex flex-col">
                                    <span>{{ $app->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $app->email }}</span>
                                    <span class="text-xs text-gray-500">{{ $app->phone }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                @if($app->course)
                                    {{ $app->course->name }}
                                    <br><span class="text-xs text-muted">{{ $app->course->university->name ?? '' }}</span>
                                @else
                                    <span class="text-gray-400">General Inquiry</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $app->intake }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span
                                    class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700 border border-yellow-200">
                                    {{ ucfirst($app->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">
                                No applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    </div>
@endsection