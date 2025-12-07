@extends('layouts.normal')

@section('title', 'Content Editor - AI Studio')

@section('chat')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/normal/dashboard.css'])
<div class="min-h-screen flex flex-col bg-gray-50 dark:bg-[#212121]">
    <!-- Top Navigation Bar -->
    <div class="bg-white dark:bg-[#212121] border-b border-gray-200 dark:border-gray-700 px-6 py-3 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-4">
            <a href="{{ route('normal.documents') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 transition-colors">
                <span class="material-icons">arrow_back</span>
            </a>
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                <span class="font-medium text-gray-900 dark:text-white">Content Editor</span>
                <span class="text-gray-400">/</span>
                <span class="flex items-center gap-1">
                    <span class="truncate max-w-[200px]">{{ $model }} Generated Content</span>
                </span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                <span class="material-icons text-[20px]">history</span>
            </button>
            <button id="save-btn" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-icons text-[18px]">save</span> Save
            </button>
            <button class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors flex items-center gap-2">
                <span class="material-icons text-[18px]">file_download</span> Export
            </button>
            <button class="px-4 py-2 bg-black dark:bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                <span class="material-icons text-[18px]">share</span> Share
            </button>
        </div>
    </div>

    <div class="flex-1 flex overflow-hidden">
        <!-- Main Editor Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-white dark:bg-[#212121] text-gray-900 dark:text-white">
            <!-- Toolbar -->
            <div id="toolbar-container" class="border-b border-gray-200 dark:border-gray-700 px-6 py-3 flex items-center gap-2 sticky top-0 bg-white dark:bg-[#212121] z-40">
                <span class="ql-formats">
                    <select class="ql-header" defaultValue="0">
                        <option value="1">Heading 1</option>
                        <option value="2">Heading 2</option>
                        <option value="3">Heading 3</option>
                        <option value="0">Normal</option>
                    </select>
                </span>
                <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-2"></div>
                <span class="ql-formats">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                </span>
                <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-2"></div>
                <span class="ql-formats">
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                    <button class="ql-indent" value="-1"></button>
                    <button class="ql-indent" value="+1"></button>
                </span>
                <div class="w-px h-6 bg-gray-200 dark:bg-gray-700 mx-2"></div>
                <span class="ql-formats">
                    <button class="ql-link"></button>
                    <button class="ql-image"></button>
                    <button class="ql-clean"></button>
                </span>
            </div>

            <!-- Editor Content -->
            <div class="flex-1 overflow-y-auto" id="editor-container">
                <div class="max-w-3xl mx-auto py-12 px-8">
                    <div id="doc-editor" class="text-sm leading-relaxed font-serif">
                        {!! $generatedContent !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="w-80 bg-white dark:bg-[#303030] border-l border-gray-200 dark:border-gray-700 flex flex-col overflow-y-auto">
            <!-- Tabs -->
            <div class="flex border-b border-gray-200 dark:border-gray-700">
                <button class="flex-1 py-3 text-xs font-semibold text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400 uppercase tracking-wide">Guidelines</button>
                <button class="flex-1 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 uppercase tracking-wide">Facts</button>
                <button class="flex-1 py-3 text-xs font-semibold text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 uppercase tracking-wide">Outline</button>
            </div>

            <div class="p-6 space-y-8">
                <!-- Content Score -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Content Score</h3>
                        <span class="material-icons text-gray-400 text-sm">info</span>
                    </div>
                    
                    <div class="relative w-40 h-20 mx-auto mb-2">
                        <div class="absolute inset-0 flex items-end justify-center pb-1">
                            <div class="text-center">
                                <span class="text-3xl font-bold text-gray-900 dark:text-white">88</span>
                                <span class="text-gray-400 text-sm">/100</span>
                            </div>
                        </div>
                        <!-- Simple SVG Gauge Mockup -->
                        <svg viewBox="0 0 100 50" class="w-full h-full transform">
                            <path d="M 10 50 A 40 40 0 0 1 90 50" fill="none" stroke="#eee" stroke-width="8" class="dark:stroke-gray-700" />
                            <path d="M 10 50 A 40 40 0 0 1 75 20" fill="none" stroke="#22c55e" stroke-width="8" stroke-dasharray="100" stroke-dashoffset="0" />
                        </svg>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 px-4">
                        <span>Avg 74</span>
                        <span>Top 77</span>
                    </div>
                </div>

                <!-- Content Structure -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Content Structure</h3>
                        <button class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                            <span class="material-icons text-[14px]">tune</span> Adjust
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Words</div>
                            <div class="font-bold text-gray-900 dark:text-white text-lg" id="word-count">0</div>
                            <div class="text-[10px] text-gray-400">2k-2.4k</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Headings</div>
                            <div class="font-bold text-gray-900 dark:text-white text-lg" id="heading-count">0</div>
                            <div class="text-[10px] text-gray-400">12-15</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 uppercase tracking-wider mb-1">Paragraphs</div>
                            <div class="font-bold text-gray-900 dark:text-white text-lg" id="paragraph-count">0</div>
                            <div class="text-[10px] text-gray-400">at least 40</div>
                        </div>
                    </div>
                </div>

                <!-- Terms -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Terms</h3>
                        <button class="text-xs text-gray-400 hover:text-gray-600 flex items-center gap-1">
                            <span class="material-icons text-[14px]">tune</span> Adjust
                        </button>
                    </div>

                    <div class="relative mb-4">
                        <span class="absolute left-3 top-2.5 text-gray-400 material-icons text-sm">search</span>
                        <input type="text" placeholder="Search terms..." class="w-full pl-9 pr-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 text-gray-900 dark:text-white placeholder-gray-400">
                    </div>

                    <div class="flex gap-2 mb-4 overflow-x-auto pb-2 no-scrollbar">
                        <button class="px-3 py-1 bg-indigo-600 text-white text-xs rounded-full whitespace-nowrap">All 76</button>
                        <button class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-full whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600">Headings 5</button>
                        <button class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs rounded-full whitespace-nowrap hover:bg-gray-200 dark:hover:bg-gray-600">NLP 75</button>
                    </div>

                    <div class="space-y-2" id="keywords-list">
                        <!-- Keywords will be populated by JS -->
                        <div class="flex items-center justify-between group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">content marketing</span>
                            <span class="text-xs font-medium text-green-600 bg-green-50 dark:bg-green-900/30 px-2 py-0.5 rounded">2/4</span>
                        </div>
                        <div class="flex items-center justify-between group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">strategy</span>
                            <span class="text-xs font-medium text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30 px-2 py-0.5 rounded">1/3</span>
                        </div>
                        <div class="flex items-center justify-between group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-1 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">audience</span>
                            <span class="text-xs font-medium text-gray-400 bg-gray-50 dark:bg-gray-700 px-2 py-0.5 rounded">0/2</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Regeneration Options Modal -->
    <!-- Regenerate Modal -->
<div id="regen-modal" class="fixed inset-0 z-[100] hidden">
  
  <!-- Backdrop -->
  <div class="absolute inset-0 bg-black/40 backdrop-blur-md"></div>

  <!-- Modal Wrapper -->
  <div class="relative flex min-h-screen items-center justify-center p-4">
    
    <!-- Modal Card -->
    <div class="w-full max-w-md rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-[#2b2b2b] shadow-2xl">
      
      <!-- Header -->
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
          <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/40">
            <span class="material-icons text-indigo-600 dark:text-indigo-400 text-[20px]">autorenew</span>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Regenerate Section
          </h3>
        </div>
        <button id="cancel-regen-btn" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
          ✕
        </button>
      </div>

      <!-- Body -->
      <div class="px-6 py-5 space-y-5">

        <!-- Instructions -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Instructions (optional)
          </label>
          <textarea
            id="regen-instructions"
            rows="3"
            placeholder="Make it more professional, Expand this..."
            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#1f1f1f] px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
          ></textarea>
        </div>

        <!-- Model Select -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            AI Model
          </label>
          <select
            id="regen-model"
            class="w-full rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-[#1f1f1f] px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:outline-none"
          >
            <option value="chatgpt">ChatGPT (GPT-4o)</option>
            <option value="claude">Claude (Opus)</option>
            <option value="gemini">Gemini (Pro)</option>
            <option value="perplexity">Perplexity</option>
          </select>
        </div>

      </div>

      <!-- Footer -->
      <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-[#252525] rounded-b-2xl">
        <button
          id="cancel-regen-btn"
          class="rounded-lg px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition"
        >
          Cancel
        </button>

        <button
          id="confirm-regen-btn"
          class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition shadow-md"
        >
          Regenerate
        </button>
      </div>
    </div>
  </div>
</div>

    
    <!-- Overlay Container for Buttons -->
    <div id="overlay-container" class="absolute top-0 left-0 w-full h-full pointer-events-none z-10 overflow-hidden"></div>
</div>

<!-- Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@vite(['resources/css/normal/document.css'])

<!-- Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    window.AppConfig = {
        csrfToken: '{{ csrf_token() }}',
        routes: {
            regenerate: '{{ route("normal.document.regenerate") }}',
            update: '{{ route("normal.document.update", ["chat" => request()->route("chat")]) }}'
        },
        model: '{{ $model ?? "chatgpt" }}'
    };
</script>
@vite(['resources/js/normal/document.js'])
@endsection
