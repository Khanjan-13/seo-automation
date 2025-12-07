@extends('layouts.normal')

@section('title', 'New Content - AI Studio')

@section('chat')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/normal/dashboard.css', 'resources/js/normal/dashboard.js'])

<div class="min-h-screen flex flex-col items-center justify-center p-6 dark:bg-[#212121]">
    <div class="w-full max-w-3xl">
        
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">What are we creating today?</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-2 text-lg">Use <kbd class="bg-gray-100 dark:bg-gray-800 px-2 py-1 rounded text-sm border dark:border-gray-700 font-mono text-gray-600 dark:text-gray-300">/</kbd> to add context, tone, or formatting.</p>
        </div>

        <form id="promptForm" method="POST" action="{{ route('normal.prompt') }}" class="relative">
            @csrf
            
            <div class="editor-wrapper bg-white dark:bg-[#303030] border border-gray-200 dark:border-gray-700 rounded-2xl p-1 dark:text-white">
                <div id="editor" 
                     contenteditable="true" 
                     data-placeholder="Describe your content... e.g., 'Write a blog post about...'"
                     class="w-full px-6 py-4 max-h-[400px] overflow-y-auto text-sm dark:text-white"></div>
                
                <div class="flex justify-between items-center px-4 py-3 bg-gray-50 dark:bg-[#212121] rounded-b-xl border-t border-gray-100 dark:border-gray-700">
                    <!-- <div class="text-xs text-gray-400 font-medium flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full"></span> AI Ready
                    </div> -->
                    <select id="aiModel" name="ai_model" class="bg-white dark:bg-[#303030] border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-white text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5">
                            <option value="chatgpt">ChatGPT 5</option>
                            <option value="claude">Claude 4.1</option>
                            <option value="gemini">Gemini Pro</option>
                            <option value="perplexity">Perplexity</option>
                        </select>
                    <div class="flex items-center gap-3">
                        
                        <button type="submit" 
                            class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <span>Run Command</span>
                            <span class="material-icons text-sm">arrow_forward</span>
                        </button>
                    </div>
                </div>
            </div>

            <div id="suggestionsBox" 
                 class="hidden absolute bg-white dark:bg-[#303030] border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl z-50 w-72 max-h-80 overflow-y-auto overflow-x-hidden top-full left-0 mt-2">
                
                <div class="p-2 bg-gray-50 dark:bg-[#303030] border-b border-gray-100 dark:border-gray-600 text-xs font-bold text-gray-400 uppercase tracking-wider dark:text-white">
                    Context & Formatting
                </div>
                
                <div id="suggestionsList" class="p-2 space-y-1 dark:text-white">
                    </div>
            </div>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            AI can make mistakes. Please review generated content.
        </p>
    </div>
</div>
@endsection