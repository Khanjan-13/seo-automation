// --- Configuration ---
const commands = [
    {
        tag: "Content Type",
        icon: "article",
        desc: "Type of content (Blog, Ad Copy, Email, etc.)",
    },
    { tag: "Brand Name", icon: "badge", desc: "Your business or brand name" },
    {
        tag: "About Brand",
        icon: "info",
        desc: "Short description of the brand",
    },
    {
        tag: "Brand Voice",
        icon: "record_voice_over",
        desc: "How the brand should sound",
    },
    {
        tag: "Content Tone",
        icon: "mood",
        desc: "Tone/style (Professional, Friendly, Bold, etc.)",
    },
    { tag: "USP", icon: "star", desc: "Unique selling points to highlight" },
    {
        tag: "Targeted Audience",
        icon: "group",
        desc: "Who the content is intended for",
    },
    {
        tag: "Targeted Location",
        icon: "location_on",
        desc: "City/Country to target for SEO",
    },
    {
        tag: "Primary Keyword",
        icon: "vpn_key",
        desc: "Main SEO keyword to focus on",
    },
    {
        tag: "Secondary Keyword",
        icon: "key",
        desc: "Additional keywords to include",
    },
    {
        tag: "Overall Reference",
        icon: "description",
        desc: "Reference or sample to follow",
    },
    { tag: "H1", icon: "title", desc: "Main Heading" },
    { tag: "H2", icon: "format_size", desc: "Subheadings" },
    { tag: "H3", icon: "text_fields", desc: "Additional smaller headings" },
    { tag: "H4", icon: "title", desc: "Minor subpoints" },
    { tag: "FAQ", icon: "help", desc: "Frequently asked questions to include" },
    { tag: "CTA", icon: "campaign", desc: "Call to action statement" },
    { tag: "Topic", icon: "topic", desc: "Main topic or idea for the content" },
    { tag: "Description", icon: "notes", desc: "Short description or summary" },
    { tag: "Words Needed", icon: "straighten", desc: "Required word count" },
    {
        tag: "Number of Paragraphs",
        icon: "list",
        desc: "How many sections/paragraphs needed",
    },
    {
        tag: "Internal Link",
        icon: "link",
        desc: "Links to internal pages for SEO",
    },
    {
        tag: "Reference Link",
        icon: "link_off",
        desc: "External links or sources to refer",
    },
    {
        tag: "Content Quality Instructions",
        icon: "checklist",
        desc: "Specific quality guidelines",
    },
];

// --- Elements ---
const editor = document.getElementById("editor");
const suggestionsBox = document.getElementById("suggestionsBox");
const suggestionsList = document.getElementById("suggestionsList");
const form = document.getElementById("promptForm");

// --- State ---
let isMenuOpen = false;
let selectedIndex = 0;
let rangeBeforeMenu = null;

// --- Initialization ---
renderSuggestions(commands);

// Load template from URL if template parameter exists
const urlParams = new URLSearchParams(window.location.search);
const templateId = urlParams.get('template');
if (templateId) {
    loadTemplateFromUrl(templateId);
}

// Function to load template from URL parameter
async function loadTemplateFromUrl(templateId) {
    try {
        const response = await fetch(`/user/templates/api/${templateId}`);
        const template = await response.json();
        
        // Load content into editor
        editor.innerHTML = template.content;
        
        // Remove template parameter from URL
        window.history.replaceState({}, document.title, window.location.pathname);
        
        // Focus editor
        editor.focus();
    } catch (error) {
        console.error("Error loading template from URL:", error);
    }
}


// --- Event Listeners ---

// 1. Typing & Slash Detection
editor.addEventListener("keyup", (e) => {
    // Ignore navigation keys in keyup, they are handled in keydown
    if (["ArrowUp", "ArrowDown", "Enter", "Escape"].includes(e.key)) return;

    const selection = window.getSelection();
    if (!selection.rangeCount) return;

    const range = selection.getRangeAt(0);
    const text = range.startContainer.textContent || "";

    // Look specifically for trigger
    const textBeforeCursor = text.substring(0, range.startOffset);
    const lastSlash = textBeforeCursor.lastIndexOf("/");

    // Close logic
    if (isMenuOpen && (e.key === " " || e.key === "Escape")) {
        hideMenu();
        return;
    }

    // Trigger logic: Slash at start OR slash after space
    if (
        lastSlash !== -1 &&
        (lastSlash === 0 ||
            textBeforeCursor[lastSlash - 1] === " " ||
            textBeforeCursor[lastSlash - 1] === "\u00A0")
    ) {
        const query = textBeforeCursor.substring(lastSlash + 1);

        // Save cursor position
        rangeBeforeMenu = range.cloneRange();
        rangeBeforeMenu.setStart(range.startContainer, lastSlash);
        rangeBeforeMenu.setEnd(range.startContainer, range.startOffset);

        filterSuggestions(query);
        showMenu();
    } else {
        hideMenu();
    }
});

// 2. Navigation (Arrow Keys)
editor.addEventListener("keydown", (e) => {
    if (!isMenuOpen) {
        // Submit on pure Enter
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            form.requestSubmit();
        }
        return;
    }

    // Get only visible items
    const activeItems = Array.from(
        suggestionsList.querySelectorAll(".menu-item:not(.hidden)")
    );

    if (activeItems.length === 0) return;

    if (e.key === "ArrowDown") {
        e.preventDefault(); // Stop cursor in text
        selectedIndex = (selectedIndex + 1) % activeItems.length;
        updateSelection(activeItems);
    } else if (e.key === "ArrowUp") {
        e.preventDefault(); // Stop cursor in text
        selectedIndex =
            (selectedIndex - 1 + activeItems.length) % activeItems.length;
        updateSelection(activeItems);
    } else if (e.key === "Enter" || e.key === "Tab") {
        e.preventDefault();
        if (activeItems[selectedIndex]) {
            insertBadge(activeItems[selectedIndex].dataset.tag);
        }
    } else if (e.key === "Escape") {
        e.preventDefault();
        hideMenu();
    }
});

// --- Core Functions ---

function updateSelection(visibleItems) {
    visibleItems.forEach((item, idx) => {
        if (idx === selectedIndex) {
            // FIX: Use the CSS class we defined in <style>
            item.classList.add("is-active");

            // Scroll into view
            item.scrollIntoView({ block: "nearest", behavior: "smooth" });
        } else {
            item.classList.remove("is-active");
        }
    });
}

function filterSuggestions(query) {
    const q = query.toLowerCase();
    let visibleCount = 0;

    const items = suggestionsList.querySelectorAll(".menu-item");
    items.forEach((item) => {
        const tag = item.dataset.tag.toLowerCase();
        if (tag.includes(q)) {
            item.classList.remove("hidden");
            visibleCount++;
        } else {
            item.classList.add("hidden");
        }
    });

    if (visibleCount === 0) {
        hideMenu();
    } else {
        // RESET index when typing filters the list
        selectedIndex = 0;
        updateSelection(
            suggestionsList.querySelectorAll(".menu-item:not(.hidden)")
        );
    }
}

function showMenu() {
    if (isMenuOpen) return;

    const selection = window.getSelection();
    if (selection.rangeCount > 0) {
        const range = selection.getRangeAt(0).cloneRange();
        range.collapse(true);

        const rect = range.getBoundingClientRect();
        const editorRect = editor.getBoundingClientRect();

        const top = rect.bottom - editorRect.top + 24;
        const left = rect.left - editorRect.left;

        suggestionsBox.style.top = `${top}px`;
        suggestionsBox.style.left = `${left}px`;
    }

    suggestionsBox.classList.remove("hidden");
    isMenuOpen = true;

    // Highlight first item immediately
    updateSelection(
        suggestionsList.querySelectorAll(".menu-item:not(.hidden)")
    );
}

function hideMenu() {
    suggestionsBox.classList.add("hidden");
    isMenuOpen = false;
    selectedIndex = 0;
}

function insertBadge(tagName) {
    const selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(rangeBeforeMenu);

    document.execCommand("delete"); // Remove the "/text"

    // Insert Badge
    const badgeHTML = `&nbsp;<span class="smart-badge" contenteditable="false">${tagName}:</span>&nbsp;`;
    document.execCommand("insertHTML", false, badgeHTML);

    hideMenu();
}

function renderSuggestions(items) {
    suggestionsList.innerHTML = items
        .map(
            (item) => `
            <div class="menu-item flex items-center gap-3 p-2.5 rounded-lg cursor-pointer transition-colors duration-150 hover:bg-gray-50" 
                 data-tag="${item.tag}"
                 onmousedown="event.preventDefault(); insertBadge('${item.tag}')">
                <div class="icon-box w-8 h-8 flex items-center justify-center rounded-md bg-gray-100 text-gray-500 transition-colors">
                    <span class="material-icons text-[18px]">${item.icon}</span>
                </div>
                <div>
                    <div class="font-semibold text-sm text-gray-800">${item.tag}</div>
                    <div class="text-xs text-gray-400">${item.desc}</div>
                </div>
            </div>
        `
        )
        .join("");
}

// --- Form Submit ---
form.addEventListener("submit", (e) => {
    // Validate that editor has content
    const editorText = editor.innerText.trim();
    
    if (!editorText || editorText.length === 0) {
        e.preventDefault();
        
        // Add shake animation to editor
        editor.parentElement.classList.add('shake-animation');
        setTimeout(() => {
            editor.parentElement.classList.remove('shake-animation');
        }, 500);
        
        // Focus the editor
        editor.focus();
        
        return false;
    }
    
    const aiModel = document.getElementById("aiModel").value;

    // Define Tag Categories
    const parentTags = ["H1", "H2", "H3", "H4", "FAQ", "CTA"];
    const childTags = [
        "Topic",
        "Description",
        "Words Needed",
        "Number of Paragraphs",
        "Internal Link",
        "Reference Link",
        "Content Quality Instructions",
    ];

    // Parse editor content into an ordered structure
    const payload = [];
    let currentParent = null;
    let lastItem = null; // Track the last item to append text to

    // Helper to add text to the last active item
    const appendText = (text) => {
        const cleanText = text.replace(/\u00A0/g, " ").trim();
        if (cleanText && lastItem) {
            lastItem.content = lastItem.content
                ? lastItem.content + " " + cleanText
                : cleanText;
        }
    };

    // Iterate through child nodes
    editor.childNodes.forEach((node) => {
        if (node.nodeType === Node.TEXT_NODE) {
            appendText(node.textContent);
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            if (node.classList.contains("smart-badge")) {
                const rawTag = node.innerText.replace(":", "").trim();

                // Create new item
                const newItem = { tag: rawTag, content: "" };

                if (parentTags.includes(rawTag)) {
                    // It's a container (Section)
                    newItem.children = [];
                    payload.push(newItem);
                    currentParent = newItem;
                    lastItem = newItem;
                } else if (childTags.includes(rawTag)) {
                    // It's a property
                    if (currentParent) {
                        currentParent.children.push(newItem);
                    } else {
                        // Orphan child, treat as root item
                        payload.push(newItem);
                    }
                    lastItem = newItem;
                } else {
                    // Global or other tag
                    // Reset parent context for globals? Or just add to root?
                    // Assuming globals break the current section context
                    currentParent = null;
                    payload.push(newItem);
                    lastItem = newItem;
                }
            } else {
                // Other elements (like divs from newlines), extract text
                appendText(node.innerText || node.textContent);
            }
        }
    });

    const promptData = {
        structure: payload,
        model: aiModel,
    };

    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "prompt_payload";
    hiddenInput.value = JSON.stringify(promptData);
    form.appendChild(hiddenInput);

    // Show loader overlay
    const loaderOverlay = document.getElementById("loaderOverlay");
    if (loaderOverlay) {
        loaderOverlay.classList.remove("hidden");
    }
});

// ===== TEMPLATE MANAGEMENT =====

const saveTemplateBtn = document.getElementById("saveTemplateBtn");
const templatesBtn = document.getElementById("templatesBtn");
const templatesDropdown = document.getElementById("templatesDropdown");
const saveTemplateModal = document.getElementById("saveTemplateModal");
const closeTemplateModal = document.getElementById("closeTemplateModal");
const cancelTemplateBtn = document.getElementById("cancelTemplateBtn");
const saveTemplateForm = document.getElementById("saveTemplateForm");
const templatesList = document.getElementById("templatesList");

// Toggle templates dropdown
templatesBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    templatesDropdown.classList.toggle("hidden");
    if (!templatesDropdown.classList.contains("hidden")) {
        loadTemplates();
    }
});

// Close dropdown when clicking outside
document.addEventListener("click", (e) => {
    if (!templatesBtn.contains(e.target) && !templatesDropdown.contains(e.target)) {
        templatesDropdown.classList.add("hidden");
    }
});

// Open save template modal
saveTemplateBtn.addEventListener("click", () => {
    const editorText = editor.innerText.trim();
    
    if (!editorText || editorText.length === 0) {
        // Add shake animation to editor
        editor.parentElement.classList.add('shake-animation');
        setTimeout(() => {
            editor.parentElement.classList.remove('shake-animation');
        }, 500);
        editor.focus();
        return;
    }
    
    saveTemplateModal.classList.remove("hidden");
    document.getElementById("templateName").focus();
});

// Close modal handlers
closeTemplateModal.addEventListener("click", () => {
    saveTemplateModal.classList.add("hidden");
    saveTemplateForm.reset();
});

cancelTemplateBtn.addEventListener("click", () => {
    saveTemplateModal.classList.add("hidden");
    saveTemplateForm.reset();
});

// Save template
saveTemplateForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    
    const name = document.getElementById("templateName").value.trim();
    const description = document.getElementById("templateDescription").value.trim();
    const content = editor.innerHTML;
    
    try {
        const response = await fetch("/user/templates/api", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]').value,
            },
            body: JSON.stringify({
                name: name,
                content: content,
                description: description || null,
            }),
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Close modal and reset form
            saveTemplateModal.classList.add("hidden");
            saveTemplateForm.reset();
            
            // Show success message (you can customize this)
            alert("Template saved successfully!");
            
            // Reload templates if dropdown is open
            if (!templatesDropdown.classList.contains("hidden")) {
                loadTemplates();
            }
        } else {
            alert("Failed to save template. Please try again.");
        }
    } catch (error) {
        console.error("Error saving template:", error);
        alert("An error occurred while saving the template.");
    }
});

// Load templates from API
async function loadTemplates() {
    try {
        const response = await fetch("/user/templates/api");
        const templates = await response.json();
        
        if (templates.length === 0) {
            templatesList.innerHTML = `
                <div class="text-center py-8 text-gray-400 dark:text-gray-500">
                    <span class="material-icons text-4xl mb-2">folder_open</span>
                    <p class="text-sm">No templates yet</p>
                </div>
            `;
        } else {
            templatesList.innerHTML = templates.map(template => `
                <div class="template-item group p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-[#212121] transition-all cursor-pointer border border-transparent hover:border-gray-200 dark:hover:border-gray-700 mb-2" data-template-id="${template.id}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1" onclick="loadTemplate(${template.id})">
                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm mb-1">${escapeHtml(template.name)}</h4>
                            ${template.description ? `<p class="text-xs text-gray-500 dark:text-gray-400 mb-2">${escapeHtml(template.description)}</p>` : ''}
                            <p class="text-xs text-gray-400 dark:text-gray-500">${formatDate(template.created_at)}</p>
                        </div>
                        <button onclick="deleteTemplate(${template.id}, event)" class="opacity-0 group-hover:opacity-100 transition-opacity text-red-500 hover:text-red-700 p-1">
                            <span class="material-icons text-sm">delete</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }
    } catch (error) {
        console.error("Error loading templates:", error);
        templatesList.innerHTML = `
            <div class="text-center py-8 text-red-400">
                <span class="material-icons text-4xl mb-2">error</span>
                <p class="text-sm">Failed to load templates</p>
            </div>
        `;
    }
}

// Load template content into editor
window.loadTemplate = async function(templateId) {
    try {
        const response = await fetch(`/user/templates/api/${templateId}`);
        const template = await response.json();
        
        // Load content into editor
        editor.innerHTML = template.content;
        
        // Close dropdown
        templatesDropdown.classList.add("hidden");
        
        // Focus editor
        editor.focus();
    } catch (error) {
        console.error("Error loading template:", error);
        alert("Failed to load template.");
    }
};

// Delete template
window.deleteTemplate = async function(templateId, event) {
    event.stopPropagation();
    
    if (!confirm("Are you sure you want to delete this template?")) {
        return;
    }
    
    try {
        const response = await fetch(`/user/templates/api/${templateId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]').value,
            },
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Reload templates
            loadTemplates();
        } else {
            alert("Failed to delete template.");
        }
    } catch (error) {
        console.error("Error deleting template:", error);
        alert("An error occurred while deleting the template.");
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

