@extends('layouts.admin')

@section('title', 'Inquiry Details')
@section('header', 'View Inquiry')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex justify-between items-center">
        <a href="{{ route('admin.contact-submissions.index') }}" class="text-sm text-gray-500 hover:text-primary-600 flex items-center gap-1 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Back to Inquiries
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 rounded-xl overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $submission->subject ?: 'General Inquiry' }}</h3>
                <p class="text-xs text-gray-500">Received on {{ $submission->created_at->format('M d, Y H:i') }}</p>
            </div>
            <form action="{{ route('admin.contact-submissions.update', $submission) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="status" onchange="this.form.submit()" class="text-xs font-semibold rounded-full px-4 py-1 border-gray-300 focus:ring-primary-500 {{ $submission->status == 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200' : 'bg-green-100 text-green-800 border-green-200' }}">
                    <option value="pending" {{ $submission->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ $submission->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </form>
        </div>
        
        <div class="p-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xl">
                    {{ substr($submission->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $submission->name }}</p>
                    <p class="text-sm text-gray-500">{{ $submission->email }} • {{ $submission->phone ?: 'No phone provided' }}</p>
                </div>
            </div>

            <div class="bg-gray-50 dark:bg-gray-750 p-6 rounded-2xl border border-gray-100 dark:border-gray-700">
                <p class="text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line">{{ $submission->message }}</p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 border-t border-gray-100 dark:border-gray-700 flex justify-end">
            <form action="{{ route('admin.contact-submissions.destroy', $submission) }}" method="POST" onsubmit="return confirm('Delete this inquiry?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-trash-can text-xs"></i> Delete Permanently
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
