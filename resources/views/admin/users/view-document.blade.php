@extends('layouts.admin')

@section('title', 'View User Document')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/normal/dashboard.css'])

<div class="mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.history', $user->id) }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
            <span class="material-icons">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Document</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Viewing document by <strong>{{ $user->name }}</strong></p>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-[#303030] rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <!-- Document Header -->
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gray-50 dark:bg-gray-800/50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <span class="material-icons text-gray-400">description</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $model }} Generated Content</span>
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Created: {{ $chat->created_at->format('M d, Y h:i A') }}
            </div>
        </div>
    </div>

    <!-- Document Content (Read-Only) -->
    <div class="p-8">
        <div class="max-w-3xl mx-auto prose prose-gray dark:prose-invert">
            <div class="ql-editor text-sm leading-relaxed">
                {!! $generatedContent !!}
            </div>
        </div>
    </div>
</div>

@endsection
