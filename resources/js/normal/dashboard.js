// --- Configuration ---
const commands = [
    { tag: "Content Type", icon: "article", desc: "Type of content (Blog, Ad Copy, Email, etc.)" },
    { tag: "Brand Name", icon: "badge", desc: "Your business or brand name" },
    { tag: "About Brand", icon: "info", desc: "Short description of the brand" },
    { 
        tag: "Brand Voice", 
        icon: "record_voice_over", 
        desc: "How the brand should sound" 
    },
    { tag: "Content Tone", icon: "mood", desc: "Tone/style (Professional, Friendly, Bold, etc.)" },
    { tag: "USP", icon: "star", desc: "Unique selling points to highlight" },
    { tag: "Targeted Audience", icon: "group", desc: "Who the content is intended for" },
    { tag: "Targeted Location", icon: "location_on", desc: "City/Country to target for SEO" },
    { tag: "Primary Keyword", icon: "vpn_key", desc: "Main SEO keyword to focus on" },
    { tag: "Secondary Keyword", icon: "key", desc: "Additional keywords to include" },
    { tag: "Overall Reference", icon: "description", desc: "Reference or sample to follow" },
    { tag: "H1", icon: "title", desc: "Main Heading" },
    { tag: "H2", icon: "format_size", desc: "Subheadings" },
    { tag: "H3", icon: "text_fields", desc: "Additional smaller headings" },
    { tag: "H4", icon: "title", desc: "Minor subpoints" },
    { tag: "FAQ", icon: "help", desc: "Frequently asked questions to include" },
    { tag: "CTA", icon: "campaign", desc: "Call to action statement" },
    { tag: "Topic", icon: "topic", desc: "Main topic or idea for the content" },
    { tag: "Description", icon: "notes", desc: "Short description or summary" },
    { tag: "Words Needed", icon: "straighten", desc: "Required word count" },
    { tag: "Number of Paragraphs", icon: "list", desc: "How many sections/paragraphs needed" },
    { tag: "Internal Link", icon: "link", desc: "Links to internal pages for SEO" },
    { tag: "Reference Link", icon: "link_off", desc: "External links or sources to refer" },
    { 
    tag: "Content Quality Instructions", 
    icon: "checklist", 
    desc: "Specific quality guidelines" 
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
    const aiModel = document.getElementById("aiModel").value;
    
    // Define Tag Categories
    const parentTags = ["H1", "H2", "H3", "H4", "FAQ", "CTA"];
    const childTags = ["Topic", "Description", "Words Needed", "Number of Paragraphs", "Internal Link", "Reference Link", "Content Quality Instructions"];
    
    // Parse editor content into an ordered structure
    const payload = [];
    let currentParent = null;
    let lastItem = null; // Track the last item to append text to

    // Helper to add text to the last active item
    const appendText = (text) => {
        const cleanText = text.replace(/\u00A0/g, " ").trim();
        if (cleanText && lastItem) {
            lastItem.content = lastItem.content ? lastItem.content + " " + cleanText : cleanText;
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
        model: aiModel
    };

    const hiddenInput = document.createElement("input");
    hiddenInput.type = "hidden";
    hiddenInput.name = "prompt_payload";
    hiddenInput.value = JSON.stringify(promptData);
    form.appendChild(hiddenInput);
});
