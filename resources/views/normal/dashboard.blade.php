@extends('layouts.normal')

@section('title', 'New Content - AI Studio')

@section('chat')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/normal/dashboard.css'])

<div class="min-h-screen flex flex-col p-6 dark:bg-[#212121] overflow-y-auto">
    <div class="w-full max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="text-center space-y-2">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white tracking-tight">Content Studio</h1>
            <p class="text-lg text-gray-500 dark:text-gray-400">Design your content strategy in seconds.</p>
        </div>

        <div class="bg-white dark:bg-[#303030] rounded-3xl border border-gray-200 dark:border-gray-700 p-8 space-y-8" id="inputSection">
            
            <!-- Step 1: Content Type Selection -->
           <div id="step1" class="space-y-4">
            <!-- Label -->
            <label class="block text-sm font-medium text-zinc-600 dark:text-zinc-300">
                Select Content Type
            </label>
            <!-- Select Wrapper -->
            <div class="relative">
                <select id="contentTypeSelect" onchange="handleContentTypeChange()" class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white/80 dark:bg-zinc-900/80 backdrop-blur px-4 py-3 text-sm font-medium text-zinc-800 dark:text-zinc-100 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 appearance-none transition cursor-pointer">
                    <option value="" disabled selected>Choose a type...</option>
                    <option value="Blog Post">Blog Post</option>
                    <option value="News Article">News Article</option>
                    <option value="Product Page">Product Page</option>
                    <option value="Landing Page">Landing Page</option>
                    <option value="Service Page">Service Page</option>
                    <option value="How-to Guide">How-to Guide</option>
                    <option value="Article">Article</option>
                    <option value="Other">Other</option>
                </select>
                <!-- Custom dropdown icon -->
                <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                    <span class="material-icons text-zinc-400">expand_more</span>
                </span>
            </div>
            <!-- Hidden Input for "Other" -->
            <div id="otherContentTypeWrapper" class="hidden">
                <input type="text" id="otherContentTypeInput" class="w-full rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white/80 dark:bg-zinc-900/80 backdrop-blur px-4 py-3 text-sm text-zinc-800 dark:text-zinc-100 placeholder-zinc-400 focus:ring-2 focus:ring-indigo-500/30 transition" placeholder="Enter custom content type...">
            </div>  
           </div>

            <!-- Step 2: Context Details -->
            <div id="step2" class="space-y-4 opacity-50 pointer-events-none transition-all duration-300">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">2. Provide Context</label>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Brand Name (Optional)</label>
                        <input type="text" id="brandNameInput" 
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white placeholder-gray-400 transition-all"
                            placeholder="e.g., Acme Corp">
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Target Location (Optional)</label>
                        <input type="text" id="locationInput" 
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white placeholder-gray-400 transition-all"
                            placeholder="e.g., New York, USA">
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Target Keywords (Optional)</label>
                    <input type="text" id="keywordsInput" 
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white placeholder-gray-400 transition-all"
                        placeholder="e.g., sustainable fashion, eco-friendly materials">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-gray-500 dark:text-gray-400">Topic <span class="text-red-500">*</span></label>
                    <input type="text" id="topicInput" 
                        class="w-full px-5 py-4 text-lg bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white placeholder-gray-400 transition-all"
                        placeholder="e.g., The benefits of organic gardening"
                        oninput="checkFormCompletion()">
                </div>
            </div>

            <!-- Step 3: Model Selection & Generate -->
            <div id="step3" class="space-y-4 opacity-50 pointer-events-none transition-all duration-300">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">3. Choose AI Model</label>
                <div class="flex gap-4 items-center">
                    <div class="relative flex-1">
                        <select id="aiModelSelect" class="w-full px-5 py-3 text-base bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent dark:text-white appearance-none transition-all cursor-pointer">
                            <option value="chatgpt">ChatGPT 5</option>
                            <option value="claude">Claude 4.1</option>
                            <option value="gemini">Gemini Pro</option>
                            <option value="perplexity">Perplexity</option>
                        </select>
                         <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                            <span class="material-icons">smart_toy</span>
                        </div>
                    </div>
                    
                    <button onclick="generateOutlines()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3.5 rounded-xl font-bold transition-all shadow-lg hover:shadow-indigo-500/30 flex items-center gap-2 transform active:scale-95">
                        <span class="material-icons">auto_awesome</span>
                        Generate Outlines
                    </button>
                </div>
            </div>
        
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden text-center py-12">
            <div class="inline-flex flex-col items-center">
                <div class="w-16 h-16 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin mb-4"></div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white animate-pulse">Designing Outlines...</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Using best practices for your selected topic.</p>
            </div>
        </div>

        <!-- Outlines Display Section (Separate View) -->
        <div id="outlinesSection" class="hidden space-y-6">
            <div class="flex justify-between items-center bg-white dark:bg-[#303030] p-6 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-1">Generated Outlines</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Review options or generate all at once.</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="backToInputs()" class="px-5 py-2.5 text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 font-bold rounded-xl transition-colors">
                        Back
                    </button>
                    <button onclick="generateAllOutlines()" class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-bold transition-all shadow-lg hover:shadow-indigo-500/30 flex items-center gap-2">
                        <span class="material-icons text-sm">auto_awesome_motion</span>
                        Generate All (Batch)
                    </button>
                </div>
            </div>

            <!-- Outlines Grid -->
            <div id="outlinesGrid" class="grid grid-cols-3 gap-4">
                <!-- Content injected by JS -->
            </div>

            <div class="flex justify-center pt-4">
                <button onclick="resetProcess()" class="text-sm text-gray-400 hover:text-red-500 transition-colors flex items-center gap-1">
                    <span class="material-icons text-sm">refresh</span> Start Over
                </button>
            </div>
        </div>

        <!-- Steps 3: Batch Generation View (Partitioned) -->
        <div id="multiGenerateSection" class="hidden h-full">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="material-icons text-indigo-500">dns</span> Batch Generation
                </h2>
                <button onclick="backToInputs()" class="px-5 py-2 text-sm text-gray-600 dark:text-gray-300 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-[#252525] transition">
                    Close Batch View
                </button>
            </div>
            
            <div id="multiGenerateGrid" class="grid gap-6 h-full">
                <!-- Dynamic Columns Injected Here -->
            </div>
        </div>
    </div>

    <!-- Edit Outline Modal -->
    <div id="editOutlineModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-[#303030] rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col transform transition-all scale-100">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-100 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Customize Structure</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto flex-grow space-y-6" id="modalBody">
                <!-- Structure inputs injected here -->
            </div>

            <!-- Modal Footer -->
            <div class="p-6 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-[#252525] rounded-b-3xl flex justify-end gap-3">
                <button onclick="closeEditModal()" class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">Cancel</button>
                <form id="generateArticleForm" method="POST" action="{{ route('normal.prompt') }}">
                    @csrf
                    <input type="hidden" name="prompt_payload" id="finalPayload">
                    <button type="button" onclick="submitFinalGeneration()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-lg hover:shadow-indigo-500/30 flex items-center gap-2">
                        <span>Generate Article</span>
                        <span class="material-icons text-sm">arrow_forward</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let currentOutline = null;
    let selectedModel = 'chatgpt';

    function handleContentTypeChange() {
        const select = document.getElementById('contentTypeSelect');
        const otherWrapper = document.getElementById('otherContentTypeWrapper');
        const step2 = document.getElementById('step2');

        if (select.value === 'Other') {
            otherWrapper.classList.remove('hidden');
            document.getElementById('otherContentTypeInput').focus();
        } else {
            otherWrapper.classList.add('hidden');
        }

        // Enable Step 2
        step2.classList.remove('opacity-50', 'pointer-events-none');
        document.getElementById('topicInput').focus();
    }

    function checkFormCompletion() {
        const topic = document.getElementById('topicInput').value;
        const step3 = document.getElementById('step3');

        if (topic.length > 3) {
            step3.classList.remove('opacity-50', 'pointer-events-none');
        } else {
            step3.classList.add('opacity-50', 'pointer-events-none');
        }
    }

    async function generateOutlines() {
        const select = document.getElementById('contentTypeSelect');
        let contentType = select.value;
        
        if (contentType === 'Other') {
            contentType = document.getElementById('otherContentTypeInput').value;
            if (!contentType) {
                alert('Please specify the content type.');
                return;
            }
        }

        const topic = document.getElementById('topicInput').value;
        const model = document.getElementById('aiModelSelect').value;
        const brandName = document.getElementById('brandNameInput').value;
        const keywords = document.getElementById('keywordsInput').value;
        const location = document.getElementById('locationInput').value;

        if (!contentType || !topic) {
            alert('Please complete all required fields.');
            return;
        }

        selectedModel = model; // Store for later usage

        // Show loading
        document.getElementById('loadingState').classList.remove('hidden');
        // Hide inputs while loading
        document.getElementById('inputSection').classList.add('hidden');

        try {
            const response = await fetch('{{ route("normal.chat.generate-outlines") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    content_type: contentType, 
                    topic: topic,
                    model: model,
                    brand_name: brandName,
                    keywords: keywords,
                    location: location
                })
            });

            const data = await response.json();

            if (data.error) {
                alert('Error: ' + data.error);
                document.getElementById('loadingState').classList.add('hidden');
                document.getElementById('inputSection').classList.remove('hidden');
                return;
            }

            renderOutlines(data.outlines);

        } catch (error) {
            console.error('Error:', error);
            alert('Something went wrong. Please try again.');
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('inputSection').classList.remove('hidden');
        }
    }

    function renderOutlines(outlines) {
        generatedOutlinesData = outlines; // Store for batch generation
        document.getElementById('loadingState').classList.add('hidden');
        
        // Switch Views: Hide Input Section, Show Outlines Section
        document.getElementById('inputSection').classList.add('hidden');
        document.getElementById('inputSection').classList.remove('opacity-50', 'pointer-events-none'); // Reset active state for when we return

        const section = document.getElementById('outlinesSection');
        const grid = document.getElementById('outlinesGrid');
        
        section.classList.remove('hidden');
        grid.innerHTML = '';

        outlines.forEach((outline, index) => {
            const delay = index * 100;
            // Store outline data in a data attribute for easier access
            const outlineJson = JSON.stringify(outline).replace(/"/g, '&quot;');
            
            const card = `
                <div class="outline-card bg-white dark:bg-[#303030] rounded-2xl p-10 border border-gray-200 dark:border-gray-700 hover:shadow-2xl hover:border-indigo-500/50 transition-all duration-500 transform translate-y-4 opacity-0 flex flex-col h-full relative group" style="animation: slideUp 0.5s forwards ${delay}ms">
                    
                    <!-- Remove Button -->
                    <button onclick="removeOutline(this)" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 dark:hover:text-red-400 opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="material-icons">delete_outline</span>
                    </button>

                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase rounded-full tracking-wide">Option ${outline.id}</span>
                    </div>

                    <div class="flex justify-between text-xs text-gray-400 mb-2">
                         <span>Approx. ${outline.word_count} words</span>
                         <span>${outline.paragraph_count} paragraphs</span>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">${outline.title}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mb-6 line-clamp-3 flex-grow">${outline.description}</p>

                    <div class="space-y-3 mb-8">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Structure Preview</h4>
                        <div class="max-h-40 overflow-y-auto space-y-2 pr-2 scrollbar-thin">
                            ${outline.structure.map(item => `
                                <div class="flex gap-2">
                                    <span class="text-indigo-500">•</span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">${item.heading}</p>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>

                    <button onclick="openEditModal(${outlineJson})" class="w-full py-3 bg-gray-900 dark:bg-white text-white dark:text-gray-900 rounded-xl font-bold hover:bg-indigo-600 dark:hover:bg-indigo-400 dark:hover:text-white transition-colors shadow-lg flex items-center justify-center gap-2">
                        <span class="material-icons text-sm">edit</span>
                        View & Edit
                    </button>
                </div>
            `;
            grid.innerHTML += card;
        });
        
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function removeOutline(button) {
        const card = button.closest('.outline-card');
        card.style.transform = 'scale(0.9) opacity(0)';
        setTimeout(() => {
            card.remove();
        }, 300);
    }

    function openEditModal(outline) {
        currentOutline = outline;
        const modal = document.getElementById('editOutlineModal');
        const modalBody = document.getElementById('modalBody');
        
        modalBody.innerHTML = ''; // Clear previous inputs

        // Generate inputs for each heading
        outline.structure.forEach((item, index) => {
            const inputGroup = `
                <div class="p-4 bg-gray-50 dark:bg-[#252525] rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between mb-2">
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Section ${index + 1}</label>
                    </div>
                    <input type="text" class="heading-input w-full bg-transparent font-semibold text-gray-900 dark:text-white border-0 border-b border-gray-300 dark:border-gray-600 focus:border-indigo-500 focus:ring-0 px-0 py-2 mb-2 placeholder-gray-400" 
                        value="${item.heading}" placeholder="Heading">
                    <textarea class="content-note-input w-full bg-transparent text-sm text-gray-600 dark:text-gray-300 border-0 focus:ring-0 px-0 resize-none placeholder-gray-400" 
                        rows="2" placeholder="Describe this section...">${item.content_note}</textarea>
                </div>
            `;
            modalBody.innerHTML += inputGroup;
        });

        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editOutlineModal').classList.add('hidden');
    }

    function submitFinalGeneration() {
        if (!currentOutline) return;

        // Gather updated structure
        const headingInputs = document.querySelectorAll('.heading-input');
        const noteInputs = document.querySelectorAll('.content-note-input');
        
        const updatedStructure = [];
        headingInputs.forEach((input, index) => {
            updatedStructure.push({
                heading: input.value,
                content_note: noteInputs[index].value
            });
        });

        // Gather Context
        const brandName = document.getElementById('brandNameInput').value;
        const keywords = document.getElementById('keywordsInput').value;
        const location = document.getElementById('locationInput').value;

        // Construct Payload
        const payload = {
            model: selectedModel,
            brand_name: brandName,
            keywords: keywords,
            location: location,
            structure: updatedStructure.map(s => ({ tag: s.heading, content: s.content_note }))
        };

        document.getElementById('finalPayload').value = JSON.stringify(payload);
        document.getElementById('generateArticleForm').submit();
    }

    function backToInputs() {
        document.getElementById('outlinesSection').classList.add('hidden');
        document.getElementById('inputSection').classList.remove('hidden');
        document.getElementById('loadingState').classList.add('hidden'); // Ensure loading is hidden
    }

    function resetProcess() {
        document.getElementById('outlinesSection').classList.add('hidden');
        document.getElementById('multiGenerateSection').classList.add('hidden'); // Reset multi-view
        document.getElementById('inputSection').classList.remove('hidden');
        
        document.getElementById('contentTypeSelect').value = "";
        document.getElementById('topicInput').value = "";
        document.getElementById('otherContentTypeWrapper').classList.add('hidden');
        document.getElementById('otherContentTypeInput').value = "";
        
        // Reset context
        document.getElementById('brandNameInput').value = "";
        document.getElementById('keywordsInput').value = "";
        document.getElementById('locationInput').value = "";
        
        // Reset steps visibility
        document.getElementById('step2').classList.add('opacity-50', 'pointer-events-none');
        document.getElementById('step3').classList.add('opacity-50', 'pointer-events-none');
    }

    // --- Batch Generation Logic ---

    // Global variable to store outlines for batch processing
    let generatedOutlinesData = [];

    function generateAllOutlines() {
       const outlines = generatedOutlinesData;
       if (!outlines || outlines.length === 0) return;

       // Toggle Sections
       document.getElementById('outlinesSection').classList.add('hidden');
       const multiSection = document.getElementById('multiGenerateSection');
       multiSection.classList.remove('hidden');

       const grid = document.getElementById('multiGenerateGrid');
       grid.innerHTML = ''; // Clear previous

       // Adjust Grid Columns based on count (max 3)
       const count = outlines.length;
       const colClass = count === 1 ? 'grid-cols-1' : (count === 2 ? 'grid-cols-2' : 'grid-cols-3');
       grid.className = `grid gap-6 h-full ${colClass}`;

       // Render Loaders/Containers for each
       outlines.forEach((outline, index) => {
           const col = document.createElement('div');
           col.id = `gen-col-${outline.id}`;
           col.className = 'bg-white dark:bg-[#303030] rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col h-[calc(100vh-12rem)] shadow-lg overflow-hidden relative';
           
           col.innerHTML = `
            <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-[#252525]">
                <h3 class="font-bold text-gray-800 dark:text-white truncate flex-1" title="${outline.title}">Option ${outline.id}: ${outline.title}</h3>
                <div class="flex items-center gap-2">
                    <span class="status-badge px-2 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-800">Generating...</span>
                    
                </div>
            </div>
            <div class="flex-1 relative overflow-hidden bg-white dark:bg-[#303030]">
                <!-- Loading Spinner (Absolute Center) -->
                <div class="loader-container absolute inset-0 flex flex-col items-center justify-center z-10 bg-white/50 dark:bg-[#303030]/50 backdrop-blur-sm">
                    <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-indigo-500 border-t-transparent mb-4"></div>
                    <p class="text-sm text-gray-500 animate-pulse">Writing article...</p>
                </div>
                <!-- Content Area (Full Size, Scrollable) -->
                <div class="content-body hidden w-full h-full overflow-y-auto p-6 prose dark:prose-invert prose-sm max-w-none"></div>
            </div>
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800 
            bg-white/70 dark:bg-zinc-900/50 backdrop-blur-sm 
            hidden action-footer">

    <a href="#"
       class="view-doc-btn w-full flex items-center justify-center gap-2
              py-3 text-sm font-medium rounded-xl
              bg-indigo-600 hover:bg-indigo-500 
              text-white transition-all shadow-sm hover:shadow-md">
       
        <span class="material-icons text-base">open_in_new</span>
        Open Full Document
    </a>

</div>

       `;
           grid.appendChild(col);
           
           // Trigger API call with staggered delay to avoid race conditions
           setTimeout(() => {
               triggerGeneration(outline, col);
           }, index * 1500); // 1.5s delay between starts
       });
    }

    async function triggerGeneration(outline, container) {
        // Collect Context
        const brandName = document.getElementById('brandNameInput').value;
        const keywords = document.getElementById('keywordsInput').value;
        const location = document.getElementById('locationInput').value;
        const model = selectedModel || 'chatgpt';

        // Convert flat structure to format expected by prompt
        const structurePayload = outline.structure.map(s => ({ tag: s.heading, content: s.content_note }));

        const payload = {
            model: model,
            brand_name: brandName,
            keywords: keywords,
            location: location,
            structure: structurePayload,
            ajax: true // Signal controller to return JSON
        };

        try {
            const response = await fetch('{{ route("normal.prompt") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (data.content) {
                // Success State
                const loader = container.querySelector('.loader-container');
                const contentBody = container.querySelector('.content-body');
                const footer = container.querySelector('.action-footer');
                const badge = container.querySelector('.status-badge');
                const btn = container.querySelector('.view-doc-btn');
                const headerBtn = container.querySelector('.view-doc-btn-header');

                if (loader) loader.classList.add('hidden');
                
                if (contentBody) {
                    contentBody.innerHTML = data.content;
                    contentBody.classList.remove('hidden');
                }
                
                if (footer) footer.classList.remove('hidden');
                if (btn) btn.href = data.redirect_url;
                
                if (headerBtn) {
                    headerBtn.href = data.redirect_url;
                    headerBtn.classList.remove('hidden');
                }

                if (badge) {
                    badge.className = 'px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800';
                    badge.innerText = 'Completed';
                }
            } else {
                showError(container);
            }

        } catch (e) {
            console.error(e);
            showError(container);
        }
    }

    function showError(container) {
         const loader = container.querySelector('.loader-container');
         const badge = container.querySelector('.status-badge');
         
         if (loader) loader.innerHTML = `<span class="material-icons text-red-500 text-4xl mb-2">error</span><p class="text-red-500">Generation Failed</p>`;
         if (badge) {
             badge.className = 'px-2 py-1 text-xs font-bold rounded bg-red-100 text-red-800';
             badge.innerText = 'Error';
         }
    }
</script>

<style>
/* ... existing styles ... */
.shake-animation {
  animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
}

@keyframes shake {
  10%, 90% { transform: translate3d(-1px, 0, 0); }
  20%, 80% { transform: translate3d(2px, 0, 0); }
  30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
  40%, 60% { transform: translate3d(4px, 0, 0); }
}

/* Simulated Typography for Generated Content */
.content-body {
    padding-bottom: 5rem; /* Extra space for scrolling */
}
.content-body h1 {
    font-size: 1.875rem;
    line-height: 2.25rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 1.5rem;
}
.dark .content-body h1 { color: #f9fafb; }

.content-body h2 {
    font-size: 1.5rem;
    line-height: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-top: 2rem;
    margin-bottom: 1rem;
}
.dark .content-body h2 { color: #f3f4f6; }

.content-body h3 {
    font-size: 1.25rem;
    line-height: 1.75rem;
    font-weight: 600;
    color: #374151;
    margin-top: 1.5rem;
    margin-bottom: 0.75rem;
}
.dark .content-body h3 { color: #e5e7eb; }

.content-body p {
    margin-bottom: 1.25rem;
    line-height: 1.75;
    color: #4b5563;
}
.dark .content-body p { color: #d1d5db; }

.content-body ul {
    list-style-type: disc;
    padding-left: 1.625rem;
    margin-bottom: 1.25rem;
    color: #4b5563;
}
.dark .content-body ul { color: #d1d5db; }

.content-body li {
    margin-bottom: 0.5rem;
}

.content-body strong {
    font-weight: 600;
    color: #111827;
}
.dark .content-body strong { color: #f9fafb; }
</style>
    
<style>
    @keyframes slideUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-down {
        animation: fadeInDown 0.3s ease-out forwards;
    }
    .scrollbar-thin::-webkit-scrollbar {
        width: 4px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 20px;
    }
    .dark .scrollbar-thin::-webkit-scrollbar-thumb {
        background-color: #475569;
    }
</style>
@endsection