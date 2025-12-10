<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Document - {{ $chat->title ?? 'Untitled' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .content-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }
        .document-content {
            
            line-height: 1.8;
            font-size: 16px;
            color: #1f2937;
        }
        
        /* Headings */
        .document-content h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            line-height: 1.2;
            color: #111827;
        }
        
        .document-content h2 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-top: 1.5rem;
            margin-bottom: 0.875rem;
            line-height: 1.3;
            color: #111827;
        }
        
        .document-content h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 1.25rem;
            margin-bottom: 0.75rem;
            line-height: 1.4;
            color: #111827;
        }
        
        .document-content h4 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            line-height: 1.4;
            color: #374151;
        }
        
        .document-content h5 {
            font-size: 1.125rem;
            font-weight: 600;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            line-height: 1.4;
            color: #374151;
        }
        
        .document-content h6 {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 0.875rem;
            margin-bottom: 0.5rem;
            line-height: 1.4;
            color: #4b5563;
        }
        
        /* Paragraphs */
        .document-content p {
            margin-bottom: 1rem;
            line-height: 1.8;
        }
        
        /* Lists */
        .document-content ul,
        .document-content ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }
        
        .document-content ul {
            list-style-type: disc;
        }
        
        .document-content ol {
            list-style-type: decimal;
        }
        
        .document-content li {
            margin-bottom: 0.5rem;
            line-height: 1.6;
        }
        
        .document-content ul ul,
        .document-content ol ol,
        .document-content ul ol,
        .document-content ol ul {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        /* Text formatting */
        .document-content strong {
            font-weight: 700;
            color: #111827;
        }
        
        .document-content em {
            font-style: italic;
        }
        
        .document-content u {
            text-decoration: underline;
        }
        
        /* Links */
        .document-content a {
            color: #4f46e5;
            text-decoration: underline;
        }
        
        .document-content a:hover {
            color: #4338ca;
        }
        
        /* Blockquotes */
        .document-content blockquote {
            border-left: 4px solid #e5e7eb;
            padding-left: 1rem;
            margin: 1rem 0;
            color: #6b7280;
            font-style: italic;
        }
        
        /* Code */
        .document-content code {
            background-color: #f3f4f6;
            padding: 0.125rem 0.25rem;
            border-radius: 0.25rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875em;
        }
        
        .document-content pre {
            background-color: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            overflow-x: auto;
            margin-bottom: 1rem;
        }
        
        .document-content pre code {
            background-color: transparent;
            padding: 0;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200 no-print">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="material-icons text-indigo-600">description</span>
                    <h1 class="text-xl font-semibold text-gray-900">{{ $chat->title ?? 'Shared Document' }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="window.print()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg flex items-center gap-2 transition-colors">
                        <span class="material-icons text-sm">print</span>
                        Print
                    </button>
                    <button onclick="copyContent()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg flex items-center gap-2 transition-colors">
                        <span class="material-icons text-sm">content_copy</span>
                        Copy
                    </button>
                    <button onclick="openInGoogleDocs()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center gap-2 transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                        </svg>
                        Open in Google Docs
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Content -->
    <div class="content-container">
        <div class="bg-white rounded-lg shadow-sm p-8 md:p-12">
            <div class="document-content">
                {!! $content !!}
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-gray-500 no-print">
            <p>This is a read-only shared document. Created with AI Content Studio.</p>
        </div>
    </div>

    <script>
        function copyContent() {
            const contentElement = document.querySelector('.document-content');
            
            // Create a temporary div to hold the content
            const tempDiv = document.createElement('div');
            tempDiv.contentEditable = true;
            tempDiv.style.position = 'absolute';
            tempDiv.style.left = '-9999px';
            tempDiv.innerHTML = contentElement.innerHTML;
            document.body.appendChild(tempDiv);
            
            // Select the content
            const range = document.createRange();
            range.selectNodeContents(tempDiv);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            
            // Copy to clipboard
            let success = false;
            try {
                success = document.execCommand('copy');
            } catch (err) {
                console.error('Copy failed:', err);
            }
            
            // Clean up
            selection.removeAllRanges();
            document.body.removeChild(tempDiv);
            
            // Show feedback
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            
            if (success) {
                btn.innerHTML = '<span class="material-icons text-sm">check</span> Copied!';
                btn.classList.add('bg-green-100', 'text-green-700');
                btn.classList.remove('bg-gray-100', 'text-gray-700');
                
                setTimeout(() => {
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('bg-green-100', 'text-green-700');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                }, 2000);
            } else {
                alert('Failed to copy content. Please try selecting and copying manually.');
            }
        }
        
        function openInGoogleDocs() {
            const contentElement = document.querySelector('.document-content');
            const title = document.querySelector('h1').textContent || 'Shared Document';
            
            // Show loading state
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="material-icons text-sm animate-spin">refresh</span> Preparing...';
            btn.disabled = true;
            
            // Create a temporary div to hold the content for copying
            const tempDiv = document.createElement('div');
            tempDiv.contentEditable = true;
            tempDiv.style.position = 'absolute';
            tempDiv.style.left = '-9999px';
            tempDiv.innerHTML = contentElement.innerHTML;
            document.body.appendChild(tempDiv);
            
            // Select the content
            const range = document.createRange();
            range.selectNodeContents(tempDiv);
            const selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
            
            // Copy to clipboard
            let copySuccess = false;
            try {
                copySuccess = document.execCommand('copy');
            } catch (err) {
                console.error('Copy failed:', err);
            }
            
            // Clean up
            selection.removeAllRanges();
            document.body.removeChild(tempDiv);
            
            if (copySuccess) {
                // Update button to show success
                btn.innerHTML = '<span class="material-icons text-sm">check</span> Content Copied!';
                btn.classList.add('bg-green-600');
                btn.classList.remove('bg-indigo-600');
                
                // Open Google Docs after a short delay
                setTimeout(() => {
                    window.open('https://docs.google.com/document/create', '_blank');
                    
                    // Show simple instruction
                    setTimeout(() => {
                        alert('✅ Content copied to clipboard!\n\n📝 A new Google Docs tab has been opened.\n\n👉 Simply paste (Ctrl+V or Cmd+V) to add your content with all formatting preserved!');
                        
                        btn.innerHTML = originalHTML;
                        btn.classList.remove('bg-green-600');
                        btn.classList.add('bg-indigo-600');
                        btn.disabled = false;
                    }, 800);
                }, 300);
            } else {
                // Fallback: download HTML file
                const htmlContent = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>${title}</title>
</head>
<body>
${contentElement.innerHTML}
</body>
</html>`;
                
                const blob = new Blob([htmlContent], { type: 'text/html' });
                const url = URL.createObjectURL(blob);
                
                const a = document.createElement('a');
                a.href = url;
                a.download = `${title.replace(/[^a-z0-9]/gi, '_')}.html`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
                
                btn.innerHTML = '<span class="material-icons text-sm">download</span> Downloaded!';
                btn.classList.add('bg-blue-600');
                btn.classList.remove('bg-indigo-600');
                
                setTimeout(() => {
                    window.open('https://docs.google.com/document/create', '_blank');
                    alert('HTML file downloaded!\n\nTo import:\n1. In Google Docs, click File → Open → Upload\n2. Select the downloaded HTML file\n\nOr open the HTML file in your browser, copy all, and paste into Google Docs.');
                    
                    btn.innerHTML = originalHTML;
                    btn.classList.remove('bg-blue-600');
                    btn.classList.add('bg-indigo-600');
                    btn.disabled = false;
                }, 500);
            }
        }
    </script>
</body>
</html>
