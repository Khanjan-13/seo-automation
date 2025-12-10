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
    let seoAnalysisTimeout = null;
    
    quill.on('text-change', function() {
        updateStats();
        
        // Debounce SEO analysis (more expensive)
        clearTimeout(seoAnalysisTimeout);
        seoAnalysisTimeout = setTimeout(() => {
            analyzeSEOContent();
            extractFacts();
            updateOutline();
        }, 500);
        
        // Debounce button update
        clearTimeout(updateButtonsTimeout);
        updateButtonsTimeout = setTimeout(updateOverlayButtons, 100);
    });

    // Stop words list for filtering
    const STOP_WORDS = new Set([
        'the', 'be', 'to', 'of', 'and', 'a', 'in', 'that', 'have', 'i',
        'it', 'for', 'not', 'on', 'with', 'he', 'as', 'you', 'do', 'at',
        'this', 'but', 'his', 'by', 'from', 'they', 'we', 'say', 'her', 'she',
        'or', 'an', 'will', 'my', 'one', 'all', 'would', 'there', 'their',
        'what', 'so', 'up', 'out', 'if', 'about', 'who', 'get', 'which', 'go',
        'me', 'when', 'make', 'can', 'like', 'time', 'no', 'just', 'him', 'know',
        'take', 'people', 'into', 'year', 'your', 'good', 'some', 'could', 'them',
        'see', 'other', 'than', 'then', 'now', 'look', 'only', 'come', 'its', 'over',
        'think', 'also', 'back', 'after', 'use', 'two', 'how', 'our', 'work',
        'first', 'well', 'way', 'even', 'new', 'want', 'because', 'any', 'these',
        'give', 'day', 'most', 'us', 'is', 'was', 'are', 'been', 'has', 'had',
        'were', 'said', 'did', 'having', 'may', 'should', 'am', 'being'
    ]);

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

    function analyzeSEOContent() {
        const text = quill.getText().trim();
        
        if (!text || text.length === 0) {
            resetSEOMetrics();
            return;
        }

        const words = text.split(/\s+/);
        const wordCount = words.length;
        const sentences = text.split(/[.!?]+/).filter(s => s.trim().length > 0);
        const sentenceCount = sentences.length;
        
        // Calculate metrics
        const keywordData = calculateKeywordDensity(text, wordCount);
        const readability = calculateReadabilityScore(text, wordCount, sentenceCount);
        const topWords = getTopWords(words, wordCount);
        const issues = detectSEOIssues(wordCount, quill.root.querySelectorAll('h1, h2, h3, h4, h5, h6').length, keywordData);
        const seoScore = calculateSEOScore(wordCount, quill.root.querySelectorAll('h1, h2, h3, h4, h5, h6').length, readability, keywordData);
        
        // Update UI
        updateKeywordsList(keywordData);
        updateReadabilityUI(readability);
        updateSEOIssues(issues);
        updateTopWords(topWords);
        updateSEOScore(seoScore);
    }

    function calculateKeywordDensity(text, totalWords) {
        const keywords = {};
        const lowerText = text.toLowerCase();
        
        // Extract 2-word and 3-word phrases
        const words = lowerText.split(/\s+/).filter(w => w.length > 2);
        
        // 2-word phrases
        for (let i = 0; i < words.length - 1; i++) {
            const phrase = words[i] + ' ' + words[i + 1];
            if (!STOP_WORDS.has(words[i]) || !STOP_WORDS.has(words[i + 1])) {
                keywords[phrase] = (keywords[phrase] || 0) + 1;
            }
        }
        
        // 3-word phrases
        for (let i = 0; i < words.length - 2; i++) {
            const phrase = words[i] + ' ' + words[i + 1] + ' ' + words[i + 2];
            keywords[phrase] = (keywords[phrase] || 0) + 1;
        }
        
        // Calculate density and filter
        const keywordArray = Object.entries(keywords)
            .map(([keyword, count]) => ({
                keyword,
                count,
                density: ((count * keyword.split(' ').length / totalWords) * 100).toFixed(2)
            }))
            .filter(k => k.count >= 2) // Only show keywords that appear at least twice
            .sort((a, b) => b.count - a.count)
            .slice(0, 20); // Top 20 keywords
        
        return keywordArray;
    }

    function calculateReadabilityScore(text, wordCount, sentenceCount) {
        if (sentenceCount === 0 || wordCount === 0) return 0;
        
        // Count syllables (simplified)
        const syllables = text.toLowerCase()
            .replace(/[^a-z]/g, ' ')
            .split(/\s+/)
            .filter(w => w.length > 0)
            .reduce((count, word) => {
                // Simple syllable counting
                const vowels = word.match(/[aeiouy]+/g);
                return count + (vowels ? vowels.length : 1);
            }, 0);
        
        // Flesch Reading Ease formula
        const avgWordsPerSentence = wordCount / sentenceCount;
        const avgSyllablesPerWord = syllables / wordCount;
        const score = 206.835 - (1.015 * avgWordsPerSentence) - (84.6 * avgSyllablesPerWord);
        
        return Math.max(0, Math.min(100, Math.round(score)));
    }

    function getTopWords(words, totalWords) {
        const wordFreq = {};
        
        words.forEach(word => {
            const cleaned = word.toLowerCase().replace(/[^a-z]/g, '');
            if (cleaned.length > 3 && !STOP_WORDS.has(cleaned)) {
                wordFreq[cleaned] = (wordFreq[cleaned] || 0) + 1;
            }
        });
        
        return Object.entries(wordFreq)
            .map(([word, count]) => ({
                word,
                count,
                percentage: ((count / totalWords) * 100).toFixed(2)
            }))
            .sort((a, b) => b.count - a.count)
            .slice(0, 10);
    }

    function detectSEOIssues(wordCount, headingCount, keywordData) {
        const issues = [];
        
        // Word count issues
        if (wordCount < 300) {
            issues.push({
                type: 'error',
                title: 'Low word count',
                message: `Content has only ${wordCount} words. Aim for at least 300 words for better SEO.`
            });
        } else if (wordCount < 1000) {
            issues.push({
                type: 'warning',
                title: 'Short content',
                message: `Content has ${wordCount} words. Consider expanding to 1000+ words for better ranking.`
            });
        }
        
        // Heading issues
        if (headingCount === 0) {
            issues.push({
                type: 'error',
                title: 'No headings',
                message: 'Add headings (H1-H6) to structure your content for better SEO.'
            });
        } else if (headingCount < 3) {
            issues.push({
                type: 'warning',
                title: 'Few headings',
                message: `Only ${headingCount} heading(s) found. Add more to improve content structure.`
            });
        }
        
        // Keyword density issues
        keywordData.forEach(kw => {
            if (parseFloat(kw.density) > 5) {
                issues.push({
                    type: 'warning',
                    title: 'Keyword stuffing',
                    message: `"${kw.keyword}" appears too frequently (${kw.density}%). Reduce to 1-3% for natural content.`
                });
            }
        });
        
        return issues;
    }

    function calculateSEOScore(wordCount, headingCount, readability, keywordData) {
        let score = 0;
        
        // Word count score (0-30 points)
        if (wordCount >= 2000 && wordCount <= 2400) {
            score += 30;
        } else if (wordCount >= 1000) {
            score += 20;
        } else if (wordCount >= 500) {
            score += 10;
        }
        
        // Heading score (0-20 points)
        if (headingCount >= 12 && headingCount <= 15) {
            score += 20;
        } else if (headingCount >= 5) {
            score += 15;
        } else if (headingCount >= 3) {
            score += 10;
        }
        
        // Readability score (0-30 points)
        if (readability >= 60) {
            score += 30;
        } else if (readability >= 40) {
            score += 20;
        } else if (readability >= 20) {
            score += 10;
        }
        
        // Keyword density score (0-20 points)
        const goodKeywords = keywordData.filter(kw => {
            const density = parseFloat(kw.density);
            return density >= 1 && density <= 3;
        });
        
        if (goodKeywords.length >= 5) {
            score += 20;
        } else if (goodKeywords.length >= 3) {
            score += 15;
        } else if (goodKeywords.length >= 1) {
            score += 10;
        }
        
        return Math.min(100, score);
    }

    function updateKeywordsList(keywordData) {
        const container = document.getElementById('keywords-list');
        
        if (keywordData.length === 0) {
            container.innerHTML = '<div class="text-center text-sm text-gray-400 py-4">No significant keywords found</div>';
            return;
        }
        
        container.innerHTML = keywordData.map(kw => {
            const density = parseFloat(kw.density);
            let colorClass = 'text-gray-600 bg-gray-100 dark:bg-gray-700';
            
            if (density >= 1 && density <= 3) {
                colorClass = 'text-green-600 bg-green-50 dark:bg-green-900/30';
            } else if (density > 3 && density <= 5) {
                colorClass = 'text-yellow-600 bg-yellow-50 dark:bg-yellow-900/30';
            } else if (density > 5) {
                colorClass = 'text-red-600 bg-red-50 dark:bg-red-900/30';
            }
            
            return `
                <div class="flex items-center justify-between group cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded">
                    <span class="text-sm text-gray-700 dark:text-gray-300 truncate flex-1">${kw.keyword}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500">${kw.count}×</span>
                        <span class="text-xs font-medium ${colorClass} px-2 py-0.5 rounded">${kw.density}%</span>
                    </div>
                </div>
            `;
        }).join('');
        
        // Add search functionality
        const searchInput = document.getElementById('keyword-search');
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const items = container.querySelectorAll('div');
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'flex' : 'none';
            });
        });
    }

    function updateReadabilityUI(score) {
        document.getElementById('readability-score').textContent = score;
        
        let label = '';
        let colorClass = '';
        
        if (score >= 90) {
            label = 'Very Easy';
            colorClass = 'text-green-600';
        } else if (score >= 80) {
            label = 'Easy';
            colorClass = 'text-green-600';
        } else if (score >= 70) {
            label = 'Fairly Easy';
            colorClass = 'text-green-500';
        } else if (score >= 60) {
            label = 'Standard';
            colorClass = 'text-blue-600';
        } else if (score >= 50) {
            label = 'Fairly Difficult';
            colorClass = 'text-yellow-600';
        } else if (score >= 30) {
            label = 'Difficult';
            colorClass = 'text-orange-600';
        } else {
            label = 'Very Difficult';
            colorClass = 'text-red-600';
        }
        
        const labelEl = document.getElementById('readability-label');
        labelEl.textContent = label;
        labelEl.className = `text-center text-xs font-medium ${colorClass}`;
    }

    function updateSEOIssues(issues) {
        const container = document.getElementById('seo-issues-list');
        const countEl = document.getElementById('issues-count');
        
        countEl.textContent = issues.length;
        
        if (issues.length === 0) {
            container.innerHTML = '<div class="flex items-center gap-2 text-green-600 dark:text-green-400 text-sm p-2 bg-green-50 dark:bg-green-900/20 rounded"><span class="material-icons text-sm">check_circle</span><span>No issues detected</span></div>';
            return;
        }
        
        container.innerHTML = issues.map(issue => {
            const icon = issue.type === 'error' ? 'error' : 'warning';
            const colorClass = issue.type === 'error' 
                ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20' 
                : 'text-yellow-600 dark:text-yellow-400 bg-yellow-50 dark:bg-yellow-900/20';
            
            return `
                <div class="${colorClass} p-3 rounded-lg">
                    <div class="flex items-start gap-2">
                        <span class="material-icons text-sm mt-0.5">${icon}</span>
                        <div class="flex-1">
                            <div class="font-medium text-xs mb-1">${issue.title}</div>
                            <div class="text-xs opacity-90">${issue.message}</div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function updateTopWords(topWords) {
        const container = document.getElementById('top-words-list');
        
        if (topWords.length === 0) {
            container.innerHTML = '<div class="text-center text-sm text-gray-400 py-2">No repeated words found</div>';
            return;
        }
        
        container.innerHTML = topWords.map((word, index) => `
            <div class="flex items-center justify-between p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-400 w-4">${index + 1}</span>
                    <span class="text-sm text-gray-700 dark:text-gray-300">${word.word}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">${word.count}×</span>
                    <span class="text-xs text-gray-400">${word.percentage}%</span>
                </div>
            </div>
        `).join('');
    }

    function updateSEOScore(score) {
        const scoreEl = document.getElementById('seo-score');
        const labelEl = document.getElementById('score-label');
        const gaugeEl = document.getElementById('score-gauge');
        
        scoreEl.textContent = score;
        
        // Update label
        if (score >= 80) {
            labelEl.textContent = 'Excellent SEO quality';
        } else if (score >= 60) {
            labelEl.textContent = 'Good SEO quality';
        } else if (score >= 40) {
            labelEl.textContent = 'Fair SEO quality';
        } else if (score > 0) {
            labelEl.textContent = 'Needs improvement';
        } else {
            labelEl.textContent = 'Start writing to see your score';
        }
        
        // Update gauge (SVG arc)
        const percentage = score / 100;
        const angle = percentage * 180; // 180 degrees for semicircle
        const radians = (angle - 180) * (Math.PI / 180);
        const x = 50 + 40 * Math.cos(radians);
        const y = 50 + 40 * Math.sin(radians);
        const largeArc = angle > 180 ? 1 : 0;
        
        gaugeEl.setAttribute('d', `M 10 50 A 40 40 0 ${largeArc} 1 ${x} ${y}`);
        
        // Update color
        if (score >= 80) {
            gaugeEl.setAttribute('stroke', '#22c55e'); // green
        } else if (score >= 60) {
            gaugeEl.setAttribute('stroke', '#3b82f6'); // blue
        } else if (score >= 40) {
            gaugeEl.setAttribute('stroke', '#eab308'); // yellow
        } else {
            gaugeEl.setAttribute('stroke', '#ef4444'); // red
        }
    }

    function resetSEOMetrics() {
        document.getElementById('seo-score').textContent = '0';
        document.getElementById('score-label').textContent = 'Start writing to see your score';
        document.getElementById('score-gauge').setAttribute('d', 'M 10 50 A 40 40 0 0 1 10 50');
        document.getElementById('keywords-list').innerHTML = '<div class="text-center text-sm text-gray-400 py-4">Start writing to see keyword analysis</div>';
        document.getElementById('readability-score').textContent = '0';
        document.getElementById('readability-label').textContent = 'No content yet';
        document.getElementById('readability-label').className = 'text-center text-xs text-gray-500 dark:text-gray-400';
        document.getElementById('seo-issues-list').innerHTML = '<div class="text-center text-sm text-gray-400 py-2">No issues detected</div>';
        document.getElementById('issues-count').textContent = '0';
        document.getElementById('top-words-list').innerHTML = '<div class="text-center text-sm text-gray-400 py-2">Start writing to see analysis</div>';
    }

    // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Update button styles
            tabButtons.forEach(btn => {
                btn.classList.remove('text-indigo-600', 'dark:text-indigo-400', 'border-b-2', 'border-indigo-600', 'dark:border-indigo-400');
                btn.classList.add('text-gray-500', 'dark:text-gray-400');
            });
            
            this.classList.remove('text-gray-500', 'dark:text-gray-400');
            this.classList.add('text-indigo-600', 'dark:text-indigo-400', 'border-b-2', 'border-indigo-600', 'dark:border-indigo-400');
            
            // Update content visibility
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            document.getElementById(`tab-content-${tabName}`).classList.remove('hidden');
        });
    });

    // --- Facts and Outline Functions ---
    
    function extractFacts() {
        const text = quill.getText().trim();
        
        if (!text || text.length === 0) {
            resetFactsPanel();
            return;
        }
        
        // Extract statistics (numbers with context)
        const statistics = extractStatistics(text);
        updateStatisticsList(statistics);
        
        // Extract dates
        const dates = extractDates(text);
        updateDatesList(dates);
        
        // Extract key facts (sentences with important keywords)
        const facts = extractKeyFacts(text);
        updateFactsList(facts);
    }
    
    function extractStatistics(text) {
        const stats = [];
        // Match patterns like "50%", "$100", "1,000 users", "25 million", etc.
        const patterns = [
            /(\d+(?:,\d{3})*(?:\.\d+)?%)/g,  // Percentages
            /(\$\d+(?:,\d{3})*(?:\.\d+)?(?:\s*(?:million|billion|trillion|k|M|B))?)/gi,  // Money
            /(\d+(?:,\d{3})*(?:\.\d+)?\s*(?:million|billion|trillion|thousand))/gi,  // Large numbers
            /(\d+(?:,\d{3})*(?:\.\d+)?(?:\s*(?:users|customers|people|items|products|sales)))/gi,  // Counts
        ];
        
        patterns.forEach(pattern => {
            const matches = text.match(pattern);
            if (matches) {
                matches.forEach(match => {
                    // Get context (surrounding words)
                    const index = text.indexOf(match);
                    const start = Math.max(0, index - 50);
                    const end = Math.min(text.length, index + match.length + 50);
                    const context = text.substring(start, end).trim();
                    
                    if (!stats.find(s => s.value === match)) {
                        stats.push({ value: match, context: context });
                    }
                });
            }
        });
        
        return stats.slice(0, 10); // Limit to 10
    }
    
    function extractDates(text) {
        const dates = [];
        // Match various date formats
        const patterns = [
            /\b(?:January|February|March|April|May|June|July|August|September|October|November|December)\s+\d{1,2},?\s+\d{4}\b/gi,
            /\b\d{1,2}\/\d{1,2}\/\d{2,4}\b/g,
            /\b\d{4}-\d{2}-\d{2}\b/g,
            /\b(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s+\d{1,2},?\s+\d{4}\b/gi,
        ];
        
        patterns.forEach(pattern => {
            const matches = text.match(pattern);
            if (matches) {
                matches.forEach(match => {
                    // Get context
                    const index = text.indexOf(match);
                    const start = Math.max(0, index - 40);
                    const end = Math.min(text.length, index + match.length + 40);
                    const context = text.substring(start, end).trim();
                    
                    if (!dates.find(d => d.value === match)) {
                        dates.push({ value: match, context: context });
                    }
                });
            }
        });
        
        return dates.slice(0, 10); // Limit to 10
    }
    
    function extractKeyFacts(text) {
        const facts = [];
        const sentences = text.split(/[.!?]+/).filter(s => s.trim().length > 20);
        
        // Keywords that indicate important facts
        const importantKeywords = [
            'important', 'significant', 'key', 'essential', 'critical', 'major',
            'primary', 'main', 'fundamental', 'crucial', 'vital', 'notable',
            'first', 'largest', 'biggest', 'most', 'best', 'top'
        ];
        
        sentences.forEach(sentence => {
            const lowerSentence = sentence.toLowerCase();
            const hasImportantKeyword = importantKeywords.some(keyword => lowerSentence.includes(keyword));
            const hasNumber = /\d/.test(sentence);
            
            if ((hasImportantKeyword || hasNumber) && sentence.trim().length > 30) {
                facts.push(sentence.trim());
            }
        });
        
        return facts.slice(0, 8); // Limit to 8
    }
    
    function updateStatisticsList(statistics) {
        const container = document.getElementById('statistics-list');
        
        if (statistics.length === 0) {
            container.innerHTML = '<div class="text-center text-sm text-gray-400 py-2">No statistics found</div>';
            return;
        }
        
        container.innerHTML = statistics.map(stat => `
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-icons text-blue-600 dark:text-blue-400 text-sm">bar_chart</span>
                    <span class="font-bold text-blue-900 dark:text-blue-100">${stat.value}</span>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">${stat.context}</p>
            </div>
        `).join('');
    }
    
    function updateDatesList(dates) {
        const container = document.getElementById('dates-list');
        
        if (dates.length === 0) {
            container.innerHTML = '<div class="text-center text-sm text-gray-400 py-2">No dates found</div>';
            return;
        }
        
        container.innerHTML = dates.map(date => `
            <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-3">
                <div class="flex items-center gap-2 mb-1">
                    <span class="material-icons text-purple-600 dark:text-purple-400 text-sm">event</span>
                    <span class="font-bold text-purple-900 dark:text-purple-100">${date.value}</span>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">${date.context}</p>
            </div>
        `).join('');
    }
    
    function updateFactsList(facts) {
        const container = document.getElementById('facts-list');
        
        if (facts.length === 0) {
            container.innerHTML = '<div class="text-center text-sm text-gray-400 py-2">Write content to extract key facts</div>';
            return;
        }
        
        container.innerHTML = facts.map((fact, index) => `
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <span class="material-icons text-green-600 dark:text-green-400 text-sm mt-0.5">check_circle</span>
                    <p class="text-xs text-gray-700 dark:text-gray-300 flex-1">${fact}</p>
                </div>
            </div>
        `).join('');
    }
    
    function resetFactsPanel() {
        document.getElementById('statistics-list').innerHTML = '<div class="text-center text-sm text-gray-400 py-2">No statistics found</div>';
        document.getElementById('dates-list').innerHTML = '<div class="text-center text-sm text-gray-400 py-2">No dates found</div>';
        document.getElementById('facts-list').innerHTML = '<div class="text-center text-sm text-gray-400 py-2">Write content to extract key facts</div>';
    }
    
    function updateOutline() {
        const container = document.getElementById('outline-list');
        const headings = quill.root.querySelectorAll('h1, h2, h3, h4, h5, h6');
        
        if (headings.length === 0) {
            container.innerHTML = `
                <div class="text-center text-sm text-gray-400 py-8">
                    <span class="material-icons text-3xl mb-2">list_alt</span>
                    <p>Add headings to create an outline</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = Array.from(headings).map((heading, index) => {
            const level = parseInt(heading.tagName.substring(1)); // Get number from H1, H2, etc.
            const text = heading.textContent.trim();
            const indent = (level - 1) * 12; // Indent based on heading level
            
            // Determine icon and color based on level
            let icon = 'article';
            let colorClass = 'text-gray-700 dark:text-gray-300';
            
            if (level === 1) {
                icon = 'title';
                colorClass = 'text-indigo-600 dark:text-indigo-400';
            } else if (level === 2) {
                icon = 'subject';
                colorClass = 'text-blue-600 dark:text-blue-400';
            } else if (level === 3) {
                icon = 'notes';
                colorClass = 'text-teal-600 dark:text-teal-400';
            }
            
            return `
                <div class="outline-item flex items-start gap-2 p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors group" 
                     style="padding-left: ${indent + 8}px"
                     data-heading-index="${index}">
                    <span class="material-icons ${colorClass} text-sm mt-0.5">${icon}</span>
                    <span class="text-sm ${colorClass} group-hover:text-indigo-600 dark:group-hover:text-indigo-400 flex-1 font-medium">${text}</span>
                </div>
            `;
        }).join('');
        
        // Add click handlers to scroll to headings
        container.querySelectorAll('.outline-item').forEach((item, index) => {
            item.addEventListener('click', () => {
                const heading = headings[index];
                if (heading) {
                    heading.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    
                    // Highlight the heading briefly
                    heading.style.backgroundColor = 'rgba(99, 102, 241, 0.1)';
                    setTimeout(() => {
                        heading.style.backgroundColor = '';
                    }, 1000);
                }
            });
        });
    }

    // Initial stats and analysis
    updateStats();
    analyzeSEOContent();
    extractFacts();
    updateOutline();

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

    // --- Title Auto-Save Functionality ---
    const titleInput = document.getElementById('document-title');
    let originalTitle = titleInput ? titleInput.value : '';

    if (titleInput) {
        // Save title on blur
        titleInput.addEventListener('blur', function() {
            const newTitle = this.value.trim();
            
            if (newTitle && newTitle !== originalTitle) {
                saveTitleToServer(newTitle);
            }
        });

        // Save title on Enter key
        titleInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.blur(); // Trigger blur event which will save
            }
        });
    }

    function saveTitleToServer(title) {
        const chatId = window.AppConfig.routes.update.match(/\/document\/(\d+)/)[1];
        
        fetch(window.AppConfig.routes.update, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.AppConfig.csrfToken
            },
            body: JSON.stringify({ title: title })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                originalTitle = title; // Update original title
                console.log('Title updated successfully');
            } else {
                console.error('Title update failed:', data.message);
                titleInput.value = originalTitle; // Revert to original
            }
        })
        .catch(error => {
            console.error('Title update error:', error);
            titleInput.value = originalTitle; // Revert to original
        });
    }

    // --- Share Functionality ---
    const shareBtn = document.getElementById('share-btn');
    const shareModal = document.getElementById('share-modal');
    const closeShareModalBtns = document.querySelectorAll('#close-share-modal, #close-share-modal-btn');
    const shareLinkInput = document.getElementById('share-link-input');
    const copyLinkBtn = document.getElementById('copy-link-btn');
    const openGoogleDocsBtn = document.getElementById('open-google-docs-btn');
    
    let currentShareUrl = '';
    let currentGoogleDocsUrl = '';

    shareBtn.addEventListener('click', function() {
        // Open modal
        shareModal.classList.remove('hidden');
        shareLinkInput.value = 'Generating link...';
        
        // Generate share link
        const chatId = window.AppConfig.routes.update.match(/\/document\/(\d+)/)[1];
        
        fetch(`/user/document/${chatId}/share`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.AppConfig.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentShareUrl = data.share_url;
                currentGoogleDocsUrl = data.google_docs_url;
                shareLinkInput.value = data.share_url;
            } else {
                shareLinkInput.value = 'Error generating link';
                alert('Failed to generate share link: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Share Error:', error);
            shareLinkInput.value = 'Error generating link';
            alert('An error occurred while generating the share link.');
        });
    });

    // Close modal
    closeShareModalBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            shareModal.classList.add('hidden');
        });
    });

    // Copy link
    copyLinkBtn.addEventListener('click', function() {
        if (currentShareUrl) {
            navigator.clipboard.writeText(currentShareUrl).then(() => {
                const originalHTML = copyLinkBtn.innerHTML;
                copyLinkBtn.innerHTML = '<span class="material-icons text-sm">check</span> Copied!';
                copyLinkBtn.classList.replace('bg-indigo-600', 'bg-green-600');
                copyLinkBtn.classList.replace('hover:bg-indigo-500', 'hover:bg-green-500');
                
                setTimeout(() => {
                    copyLinkBtn.innerHTML = originalHTML;
                    copyLinkBtn.classList.replace('bg-green-600', 'bg-indigo-600');
                    copyLinkBtn.classList.replace('hover:bg-green-500', 'hover:bg-indigo-500');
                }, 2000);
            }).catch(err => {
                alert('Failed to copy link to clipboard');
            });
        }
    });

    // Open in Google Docs
    openGoogleDocsBtn.addEventListener('click', function() {
        if (currentGoogleDocsUrl) {
            window.open(currentGoogleDocsUrl, '_blank');
        }
    });
});
