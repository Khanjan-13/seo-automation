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
                    <div class="relative">
                        <select
                            id="aiModel"
                            name="ai_model"
                            class="appearance-none w-full rounded-full border border-zinc-200 
                                    dark:border-zinc-700 bg-white/80 dark:bg-zinc-900/80 backdrop-blur
                                    px-4 py-2 text-sm font-medium text-zinc-800 dark:text-zinc-100
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500/30
                                    hover:bg-zinc-50 dark:hover:bg-zinc-800
                                    transition-all duration-200">
                            <option value="chatgpt">ChatGPT 5</option>
                            <option value="claude">Claude 4.1</option>
                            <option value="gemini">Gemini Pro</option>
                            <option value="perplexity">Perplexity</option>
                        </select>

                        <!-- Custom dropdown arrow -->
                        <span class="pointer-events-none absolute inset-y-0 right-1 flex items-center">
                            <span class="material-icons text-[18px] text-zinc-400">
                                expand_more
                            </span>
                        </span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Save as Template Button -->
                        <button
                            type="button"
                            id="saveTemplateBtn"
                            class="group inline-flex items-center gap-2 rounded-full border border-zinc-200 
                                        dark:border-zinc-700 bg-white/80 dark:bg-zinc-900/80 backdrop-blur
                                        px-4 py-2 text-sm font-medium text-zinc-800 dark:text-zinc-100
                                        hover:bg-zinc-50 dark:hover:bg-zinc-800
                                            transition-all duration-200">
                              <span class="material-icons text-[18px] text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300">
                                bookmark_add
                            </span>

                            <span>Save Template</span>
                        </button>


                        <!-- Templates Dropdown -->
                        <div class="relative">
                            <button
                                type="button"
                                id="templatesBtn"
                                class="group inline-flex items-center gap-2 rounded-full border border-zinc-200 
                                        dark:border-zinc-700 bg-white/80 dark:bg-zinc-900/80 backdrop-blur
                                        px-4 py-2 text-sm font-medium text-zinc-800 dark:text-zinc-100
                                        hover:bg-zinc-50 dark:hover:bg-zinc-800
                                        transition-all duration-200"
                                >
                                <span class="material-icons text-[18px] text-zinc-500 group-hover:text-zinc-700 dark:group-hover:text-zinc-300">
                                    folder
                                </span>

                                <span>Templates</span>

                                <span class="material-icons text-[18px] text-zinc-400 group-hover:text-zinc-600 transition-transform duration-200 group-hover:rotate-180">
                                    expand_more
                                </span>
                                </button>

                            
                            <!-- Templates Dropdown Menu -->
                            <div id="templatesDropdown" 
                                class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-[#303030] border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl z-50">
                                <div class="p-3 bg-gray-50 dark:bg-[#212121] border-b border-gray-200 dark:border-gray-700">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">My Templates</h3>
                                </div>
                                <div id="templatesList" class="p-2 max-h-64 overflow-y-auto">
                                    <!-- Templates will be loaded here -->
                                    <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                                        <span class="material-icons text-4xl mb-2">folder_open</span>
                                        <p class="text-sm">No templates yet</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
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

    <!-- Save Template Modal -->
    <div id="saveTemplateModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white dark:bg-[#303030] rounded-3xl shadow-2xl p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Save as Template</h2>
                <button id="closeTemplateModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <span class="material-icons">close</span>
                </button>
            </div>
            
            <form id="saveTemplateForm">
                <div class="mb-4">
                    <label for="templateName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Template Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                        id="templateName" 
                        name="name"
                        required
                        placeholder="e.g., Blog Post Template"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                </div>
                
                <div class="mb-6">
                    <label for="templateDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description (Optional)
                    </label>
                    <textarea 
                        id="templateDescription" 
                        name="description"
                        rows="3"
                        placeholder="Brief description of this template..."
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none"></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" 
                        id="cancelTemplateBtn"
                        class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">
                        Save Template
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modern Loader Overlay -->
    <div id="loaderOverlay" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="loader-container bg-white dark:bg-[#303030] rounded-3xl shadow-2xl p-8 max-w-sm w-full mx-4 transform transition-all">
            <!-- Animated Spinner -->
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <!-- Outer Ring -->
                    <div class="w-20 h-20 border-4 border-gray-200 dark:border-gray-700 rounded-full"></div>
                    <!-- Spinning Ring -->
                    <div class="absolute top-0 left-0 w-20 h-20 border-4 border-transparent border-t-indigo-600 border-r-indigo-600 rounded-full animate-spin"></div>
                    <!-- Inner Pulse -->
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-10 h-10 bg-indigo-600/20 rounded-full animate-pulse"></div>
                </div>
            </div>
            
            <!-- Status Text -->
            <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Processing Your Request</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">AI is generating your content...</p>
                
                <!-- Progress Dots -->
                <div class="flex justify-center gap-2">
                    <div class="w-2 h-2 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="w-2 h-2 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="w-2 h-2 bg-indigo-600 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection