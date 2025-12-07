document.addEventListener('DOMContentLoaded', function() {
    // Define Skeleton Blot
    const BlockEmbed = Quill.import('blots/block/embed');
    
    class SkeletonBlot extends BlockEmbed {
        static create(value) {
            let node = super.create();
            node.setAttribute('contenteditable', 'false');
            node.innerHTML = `
                <div class="skeleton-line"></div>
                <div class="skeleton-line"></div>
                <div class="skeleton-line"></div>
                <div class="skeleton-line"></div>
            `;
            return node;
        }
    }
    SkeletonBlot.blotName = 'skeleton';
    SkeletonBlot.tagName = 'div';
    SkeletonBlot.className = 'skeleton-loader';
    
    Quill.register(SkeletonBlot);

    var quill = new Quill('#doc-editor', {
        modules: {
            toolbar: '#toolbar-container'
        },
        theme: 'snow',
        placeholder: 'Start writing...'
    });

    // Live Stats Calculation
    quill.on('text-change', function() {
        updateStats();
        // Debounce button update
        clearTimeout(updateButtonsTimeout);
        updateButtonsTimeout = setTimeout(updateOverlayButtons, 100);
    });

    function updateStats() {
        const text = quill.getText();
        const words = text.trim().length > 0 ? text.trim().split(/\s+/).length : 0;
        document.getElementById('word-count').innerText = words;

        // Count headings (approximate via DOM)
        const editorRoot = quill.root;
        const headings = editorRoot.querySelectorAll('h1, h2, h3, h4, h5, h6').length;
        document.getElementById('heading-count').innerText = headings;

        // Count paragraphs
        const paragraphs = editorRoot.querySelectorAll('p').length;
        document.getElementById('paragraph-count').innerText = paragraphs;
    }

    // Initial stats
    updateStats();

    // --- Regenerate Overlay Buttons & Modal Logic ---
    
    const overlayContainer = document.getElementById('overlay-container');
    const modal = document.getElementById('regen-modal');
    const confirmBtn = document.getElementById('confirm-regen-btn');
    const cancelBtn = document.getElementById('cancel-regen-btn');
    const instructionsInput = document.getElementById('regen-instructions');
    const modelSelect = document.getElementById('regen-model');
    
    let currentHeadingForRegen = null;
    let updateButtonsTimeout = null;

    // Function to update/sync overlay buttons
    function updateOverlayButtons() {
        // Clear existing buttons
        overlayContainer.innerHTML = '';
        
        const headings = quill.root.querySelectorAll('h1, h2, h3, h4, h5, h6');
        const editorRect = quill.root.getBoundingClientRect();
        const containerRect = overlayContainer.getBoundingClientRect(); // Should match editor area roughly

        headings.forEach((heading, index) => {
            const rect = heading.getBoundingClientRect();
            
            // Calculate position relative to the overlay container
            const top = rect.top - containerRect.top;
            const left = rect.right - containerRect.left + 10; // 10px to the right of heading
            
            // Only show if visible (simple check)
            if (top < 0 || top > containerRect.height) return;

            const btn = document.createElement('div');
            btn.className = 'regenerate-overlay-btn';
            btn.innerHTML = '<span class="material-icons text-[14px]">autorenew</span> Regenerate';
            btn.style.top = `${top}px`;
            btn.style.left = `${left}px`;
            btn.onclick = () => openRegenModal(heading);
            
            overlayContainer.appendChild(btn);
        });
    }

    // Update buttons on scroll
    const editorScrollContainer = document.getElementById('editor-container');
    editorScrollContainer.addEventListener('scroll', function() {
        updateOverlayButtons();
    });
    
    // Also update on window resize
    window.addEventListener('resize', updateOverlayButtons);
    
    // Initial call
    setTimeout(updateOverlayButtons, 500); // Wait for layout

    // Modal Functions
    function openRegenModal(heading) {
        currentHeadingForRegen = heading;
        instructionsInput.value = ''; // Reset instructions
        
        modal.classList.remove('hidden');
        instructionsInput.focus();
    }

    function closeRegenModal() {
        modal.classList.add('hidden');
        currentHeadingForRegen = null;
    }

    cancelBtn.addEventListener('click', closeRegenModal);

    confirmBtn.addEventListener('click', function() {
        if (currentHeadingForRegen) {
            handleRegenerate(currentHeadingForRegen);
            closeRegenModal();
        }
    });

    function handleRegenerate(headingElement) {
        // Find the section content using DOM traversal for extraction (easier than range text)
        // But we need indices for Quill manipulation
        
        const headingBlot = Quill.find(headingElement);
        if (!headingBlot) {
            console.error('Could not find Quill blot for heading');
            return;
        }
        
        const headingIndex = quill.getIndex(headingBlot);
        const startIndex = headingIndex + headingBlot.length();
        
        // Find next heading to determine end index
        let nextHeading = headingElement.nextElementSibling;
        while (nextHeading && !['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(nextHeading.tagName)) {
            nextHeading = nextHeading.nextElementSibling;
        }
        
        let endIndex;
        if (nextHeading) {
            const nextHeadingBlot = Quill.find(nextHeading);
            endIndex = quill.getIndex(nextHeadingBlot);
        } else {
            endIndex = quill.getLength();
        }
        
        const sectionLength = endIndex - startIndex;
        
        // Extract content for API (we can use getHTML-like approach or just text)
        // For better context, let's grab the HTML of the range
        // Quill doesn't have a direct getHTML(index, length), but we can construct it or use getText
        // Let's stick to the DOM extraction for the *content* string, but use Quill for *replacement*
        
        let sectionContent = '';
        let currentNode = headingElement.nextElementSibling;
        while (currentNode && currentNode !== nextHeading) {
            sectionContent += currentNode.outerHTML;
            currentNode = currentNode.nextElementSibling;
        }

        if (!sectionContent.trim() && sectionLength <= 1) { // <= 1 because of newline
            alert('This section is empty. Please write some content to regenerate.');
            return;
        }

        // Get context
        let context = '';
        let prevNode = headingElement.previousElementSibling;
        let charCount = 0;
        while (prevNode && charCount < 1000) {
            context = prevNode.innerText + '\n' + context;
            charCount += prevNode.innerText.length;
            prevNode = prevNode.previousElementSibling;
        }

        // --- Quill Manipulation ---
        
        // 1. Delete old content
        // We want to preserve the newline that separates this section from the next heading
        // so we don't merge them.
        let deleteLength = sectionLength;
        if (nextHeading && deleteLength > 0) {
            deleteLength = deleteLength - 1;
        }
        
        quill.deleteText(startIndex, deleteLength);
        
        // 2. Insert Skeleton
        quill.insertEmbed(startIndex, 'skeleton', true);
        
        // Call API
        fetch(window.AppConfig.routes.regenerate, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.AppConfig.csrfToken
            },
            body: JSON.stringify({
                section_content: sectionContent,
                context: context,
                model: modelSelect.value,
                instructions: instructionsInput.value
            })
        })
        .then(response => response.json())
        .then(data => {
            // 3. Remove Skeleton (it's length 1)
            quill.deleteText(startIndex, 1);

            if (data.success) {
                // 4. Insert New Content
                // dangerouslyPasteHTML inserts at index
                quill.clipboard.dangerouslyPasteHTML(startIndex, data.content);
                
                // Trigger save
                isDirty = true;
                saveBtn.classList.add('ring-2', 'ring-blue-300');
                
                // Update buttons positions
                setTimeout(updateOverlayButtons, 100);
                
            } else {
                // Restore old content if failed
                // We need to be careful about what we restore since we didn't delete the last newline
                // But dangerouslyPasteHTML might handle it. 
                // Actually, if we didn't delete the last newline, sectionContent (which includes it) might duplicate it?
                // sectionContent was extracted via DOM, so it includes everything.
                // If we restore, we might want to delete that leftover newline first?
                // Or just paste. Let's just paste for now, error case is rare.
                quill.clipboard.dangerouslyPasteHTML(startIndex, sectionContent);
                console.error('Regeneration Error (Server):', data.message);
                alert('Error regenerating content: ' + data.message);
            }
        })
        .catch(error => {
            // Remove skeleton and restore
            quill.deleteText(startIndex, 1);
            quill.clipboard.dangerouslyPasteHTML(startIndex, sectionContent);
            
            console.error('Regeneration Error (Network/Client):', error);
            alert('An error occurred. Check console for details.');
        });
    }

    // Save Functionality
    let isDirty = false;
    const saveBtn = document.getElementById('save-btn');
    const originalContent = quill.root.innerHTML;

    quill.on('text-change', function() {
        if (quill.root.innerHTML !== originalContent) {
            isDirty = true;
            saveBtn.classList.add('ring-2', 'ring-blue-300'); // Visual cue
        }
        updateStats();
    });

    // Warning before leaving with unsaved changes
    window.addEventListener('beforeunload', function (e) {
        if (isDirty) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Save Button Click
    saveBtn.addEventListener('click', function() {
        const content = quill.root.innerHTML;
        const originalBtnText = saveBtn.innerHTML;
        
        // Loading state
        saveBtn.innerHTML = '<span class="material-icons text-[18px] animate-spin">refresh</span> Saving...';
        saveBtn.disabled = true;

        fetch(window.AppConfig.routes.update, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.AppConfig.csrfToken
            },
            body: JSON.stringify({ content: content })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                isDirty = false;
                saveBtn.classList.remove('ring-2', 'ring-blue-300');
                
                // Show success feedback temporarily
                saveBtn.innerHTML = '<span class="material-icons text-[18px]">check</span> Saved';
                saveBtn.classList.replace('bg-blue-600', 'bg-green-600');
                saveBtn.classList.replace('hover:bg-blue-700', 'hover:bg-green-700');
                
                setTimeout(() => {
                    saveBtn.innerHTML = originalBtnText;
                    saveBtn.classList.replace('bg-green-600', 'bg-blue-600');
                    saveBtn.classList.replace('hover:bg-green-700', 'hover:bg-blue-700');
                    saveBtn.disabled = false;
                }, 2000);
            } else {
                console.error('Save Error (Server):', data.message);
                alert('Error saving document: ' + data.message);
                saveBtn.innerHTML = originalBtnText;
                saveBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Save Error (Network/Client):', error);
            alert('An error occurred while saving.');
            saveBtn.innerHTML = originalBtnText;
            saveBtn.disabled = false;
        });
    });
});
