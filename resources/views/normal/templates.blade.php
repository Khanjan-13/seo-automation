@extends('layouts.normal')

@section('title', 'Templates')

@section('chat')

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<div class="min-h-screen p-6 dark:bg-[#212121]">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">My Templates</h1>
                <p class="text-gray-600 dark:text-gray-400">Manage and reuse your saved prompt templates</p>
            </div>
            <a href="{{ route('normal.dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium transition duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                <span class="material-icons text-sm">add</span>
                Create New
            </a>
        </div>

        <!-- Templates Grid -->
        <div id="templatesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Templates will be loaded here -->
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-icons text-gray-400 dark:text-gray-500 text-3xl">folder_special</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No templates found</h3>
            <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">You haven't saved any templates yet. Create a prompt and save it as a template.</p>
            <a href="{{ route('normal.dashboard') }}" class="text-indigo-600 dark:text-indigo-400 font-medium hover:text-indigo-700 dark:hover:text-indigo-300 hover:underline">
                Go to Dashboard
            </a>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-16">
            <div class="inline-block w-12 h-12 border-4 border-gray-200 dark:border-gray-700 border-t-indigo-600 rounded-full animate-spin"></div>
            <p class="mt-4 text-gray-500 dark:text-gray-400">Loading templates...</p>
        </div>

    </div>
</div>

<!-- Edit Template Modal -->
<div id="editTemplateModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#303030] rounded-3xl shadow-2xl p-8 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Template</h2>
            <button id="closeEditModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <span class="material-icons">close</span>
            </button>
        </div>
        
        <form id="editTemplateForm">
            <input type="hidden" id="editTemplateId">
            
            <div class="mb-4">
                <label for="editTemplateName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Template Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                    id="editTemplateName" 
                    required
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            </div>
            
            <div class="mb-6">
                <label for="editTemplateDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Description (Optional)
                </label>
                <textarea 
                    id="editTemplateDescription" 
                    rows="3"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-[#212121] border border-gray-200 dark:border-gray-700 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all resize-none"></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" 
                    id="cancelEditBtn"
                    class="flex-1 px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                    Cancel
                </button>
                <button type="submit" 
                    class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all shadow-md hover:shadow-lg">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load templates on page load
document.addEventListener('DOMContentLoaded', function() {
    loadTemplates();
});

const templatesGrid = document.getElementById('templatesGrid');
const emptyState = document.getElementById('emptyState');
const loadingState = document.getElementById('loadingState');
const editTemplateModal = document.getElementById('editTemplateModal');
const closeEditModal = document.getElementById('closeEditModal');
const cancelEditBtn = document.getElementById('cancelEditBtn');
const editTemplateForm = document.getElementById('editTemplateForm');

// Load all templates
async function loadTemplates() {
    try {
        loadingState.classList.remove('hidden');
        templatesGrid.innerHTML = '';
        emptyState.classList.add('hidden');
        
        const response = await fetch('/user/templates/api');
        const templates = await response.json();
        
        loadingState.classList.add('hidden');
        
        if (templates.length === 0) {
            emptyState.classList.remove('hidden');
        } else {
            renderTemplates(templates);
        }
    } catch (error) {
        console.error('Error loading templates:', error);
        loadingState.classList.add('hidden');
        templatesGrid.innerHTML = `
            <div class="col-span-full text-center py-8 text-red-500">
                <span class="material-icons text-4xl mb-2">error</span>
                <p>Failed to load templates</p>
            </div>
        `;
    }
}

// Render templates
function renderTemplates(templates) {
    templatesGrid.innerHTML = templates.map(template => `
        <div class="bg-white dark:bg-[#303030] rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">${escapeHtml(template.name)}</h3>
                    ${template.description ? `<p class="text-sm text-gray-500 dark:text-gray-400 mb-3">${escapeHtml(template.description)}</p>` : ''}
                    <p class="text-xs text-gray-400 dark:text-gray-500">${formatDate(template.created_at)}</p>
                </div>
            </div>
            
            <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <button onclick="useTemplate(${template.id})" class="flex-1 flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-all text-sm">
                    <span class="material-icons text-sm">play_arrow</span>
                    Use Template
                </button>
                <button onclick="editTemplate(${template.id})" class="flex items-center justify-center gap-1 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg font-medium transition-all text-sm">
                    <span class="material-icons text-sm">edit</span>
                </button>
                <button onclick="deleteTemplate(${template.id})" class="flex items-center justify-center gap-1 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 px-4 py-2 rounded-lg font-medium transition-all text-sm">
                    <span class="material-icons text-sm">delete</span>
                </button>
            </div>
        </div>
    `).join('');
}

// Use template (redirect to dashboard with template loaded)
window.useTemplate = function(templateId) {
    window.location.href = `{{ route('normal.dashboard') }}?template=${templateId}`;
};

// Edit template
window.editTemplate = async function(templateId) {
    try {
        const response = await fetch(`/user/templates/api/${templateId}`);
        const template = await response.json();
        
        document.getElementById('editTemplateId').value = template.id;
        document.getElementById('editTemplateName').value = template.name;
        document.getElementById('editTemplateDescription').value = template.description || '';
        
        editTemplateModal.classList.remove('hidden');
    } catch (error) {
        console.error('Error loading template:', error);
        alert('Failed to load template for editing.');
    }
};

// Close edit modal
closeEditModal.addEventListener('click', () => {
    editTemplateModal.classList.add('hidden');
});

cancelEditBtn.addEventListener('click', () => {
    editTemplateModal.classList.add('hidden');
});

// Save edited template
editTemplateForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const templateId = document.getElementById('editTemplateId').value;
    const name = document.getElementById('editTemplateName').value.trim();
    const description = document.getElementById('editTemplateDescription').value.trim();
    
    try {
        const response = await fetch(`/user/templates/api/${templateId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                name: name,
                description: description || null,
            }),
        });
        
        const data = await response.json();
        
        if (data.success) {
            editTemplateModal.classList.add('hidden');
            loadTemplates();
        } else {
            alert('Failed to update template.');
        }
    } catch (error) {
        console.error('Error updating template:', error);
        alert('An error occurred while updating the template.');
    }
});

// Delete template
window.deleteTemplate = async function(templateId) {
    if (!confirm('Are you sure you want to delete this template?')) {
        return;
    }
    
    try {
        const response = await fetch(`/user/templates/api/${templateId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        });
        
        const data = await response.json();
        
        if (data.success) {
            loadTemplates();
        } else {
            alert('Failed to delete template.');
        }
    } catch (error) {
        console.error('Error deleting template:', error);
        alert('An error occurred while deleting the template.');
    }
};

// Helper functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) {
        return "Today";
    } else if (diffDays === 1) {
        return "Yesterday";
    } else if (diffDays < 7) {
        return `${diffDays} days ago`;
    } else {
        return date.toLocaleDateString();
    }
}
</script>

@endsection
