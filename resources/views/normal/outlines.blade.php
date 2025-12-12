@extends('layouts.normal')

@section('title', 'My Outlines - AI Studio')

@section('chat')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
@vite(['resources/css/normal/dashboard.css'])

<div class="min-h-screen flex flex-col p-6 dark:bg-[#212121] overflow-y-auto">
    <div class="w-full max-w-7xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">My Outlines</h1>
                <p class="text-gray-500 dark:text-gray-400">Review your saved generated structures.</p>
            </div>
            <a href="{{ route('normal.dashboard') }}" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-lg hover:shadow-indigo-500/30">
                <span class="material-icons align-middle text-sm mr-1">add</span> New
            </a>
        </div>

        @if($outlines->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($outlines as $outlineRecord)
                    <!-- We iterate through the generated outlines JSON array stored in each DB record -->
                    <!-- Wait, each DB record stores 3 options in 'generated_outlines' column as JSON -->
                    <!-- I should probably show 1 *Database Record* as a group, OR flatten them? -->
                    <!-- User said "saved in table... so that can view it in future" -->
                    <!-- I'll display the *Database Entry* as a card, perhaps clicking it shows the 3 options? -->
                    <!-- Or, simpler: Just show the main topic/type card, and a "View" button opening a modal with the 3 options? -->
                    <!-- Let's do: Card showing Topic, Type, Date. "View Options" button. -->

                    <div class="bg-white dark:bg-[#303030] rounded-2xl p-6 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-xs font-bold uppercase rounded-full tracking-wide">{{ $outlineRecord->content_type }}</span>
                            <span class="text-xs text-gray-400">{{ $outlineRecord->created_at->diffForHumans() }}</span>
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">{{ $outlineRecord->topic }}</h3>
                        
                        <div class="space-y-1 mb-6 text-sm text-gray-500 dark:text-gray-400">
                             @if($outlineRecord->brand_name)<p><span class="font-semibold">Brand:</span> {{ $outlineRecord->brand_name }}</p>@endif
                             @if($outlineRecord->keywords)<p><span class="font-semibold">Keywords:</span> {{ \Illuminate\Support\Str::limit($outlineRecord->keywords, 30) }}</p>@endif
                        </div>

                        <button onclick='openViewModal(@json($outlineRecord))' class="w-full py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center gap-2">
                            <span class="material-icons text-sm">visibility</span>
                            View Options
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $outlines->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                    <span class="material-icons text-gray-400 text-3xl">topic</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No outlines found</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Start by generating your first outline.</p>
                <a href="{{ route('normal.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-lg hover:shadow-indigo-500/30">
                    Create New
                </a>
            </div>
        @endif
    </div>

    <!-- View Modal -->
    <div id="viewOutlineModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white dark:bg-[#303030] rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col transform transition-all scale-100">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-6 border-b border-gray-100 dark:border-gray-700">
                <div>
                   <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modalTopic">Topic</h3>
                   <span class="text-sm text-gray-500 dark:text-gray-400" id="modalMeta">Type • Date</span>
                </div>
                <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                    <span class="material-icons">close</span>
                </button>
            </div>

            <!-- Modal Body: Tabs for different options -->
            <div class="p-6 overflow-y-auto flex-grow space-y-6" id="modalContent">
                <!-- Dynamically injected content -->
            </div>
            
            <!-- Hidden Form for Generation -->
            <form id="generateArticleForm" method="POST" action="{{ route('normal.prompt') }}">
                 @csrf
                 <input type="hidden" name="prompt_payload" id="finalPayload">
            </form>
        </div>
    </div>
</div>

<script>
    let currentRecord = null;

    function openViewModal(record) {
        currentRecord = record;
        document.getElementById('modalTopic').innerText = record.topic;
        document.getElementById('modalMeta').innerText = `${record.content_type} • ${new Date(record.created_at).toLocaleDateString()}`;
        
        const content = document.getElementById('modalContent');
        content.innerHTML = '';

        // Iterate through the 3 saved outlines
        const outlinesArray = record.generated_outlines; // This is the JSON array saved
        
        // Create a grid of options inside modal
        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-1 gap-6';

        outlinesArray.forEach((option) => {
             const card = document.createElement('div');
             card.className = 'border border-gray-200 dark:border-gray-700 rounded-xl p-5 bg-gray-50 dark:bg-[#252525]';
             
             // Build structure HTML
             let structureHtml = '<div class="space-y-2 mt-4 max-h-40 overflow-y-auto scrollbar-thin">';
             option.structure.forEach(s => {
                 structureHtml += `
                    <div class="flex gap-2">
                        <span class="text-indigo-500">•</span>
                        <div class="text-sm">
                            <span class="font-semibold text-gray-800 dark:text-gray-200">${s.heading}</span>
                            <p class="text-gray-500 text-xs">${s.content_note || ''}</p>
                        </div>
                    </div>
                 `;
             });
             structureHtml += '</div>';

             card.innerHTML = `
                <div class="flex justify-between items-start">
                    <h4 class="font-bold text-gray-900 dark:text-white text-lg">Option ${option.id}: ${option.title}</h4>
                </div>
                <p class="text-sm text-gray-500 mt-1">${option.description}</p>
                ${structureHtml}
                <button onclick='useOutline(${JSON.stringify(option).replace(/'/g, "&#39;")})' class="mt-4 w-full py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition">
                    Generate Article
                </button>
             `;
             grid.appendChild(card);
        });

        content.appendChild(grid);
        document.getElementById('viewOutlineModal').classList.remove('hidden');
    }

    function closeViewModal() {
        document.getElementById('viewOutlineModal').classList.add('hidden');
    }

    function useOutline(outlineOption) {
        // Construct Payload with Context from the stored record
        const payload = {
            model: currentRecord.model,
            brand_name: currentRecord.brand_name,
            keywords: currentRecord.keywords,
            location: currentRecord.location,
            structure: outlineOption.structure.map(s => ({ tag: s.heading, content: s.content_note }))
        };

        document.getElementById('finalPayload').value = JSON.stringify(payload);
        document.getElementById('generateArticleForm').submit();
    }
</script>

<style>
    .scrollbar-thin::-webkit-scrollbar { width: 4px; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    .dark .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #475569; }
</style>
@endsection
