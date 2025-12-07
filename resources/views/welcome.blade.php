<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SEO Master - Enterprise Grade SEO Automation</title>
    <meta name="description" content="The AI copilot for enterprise SEO teams. Scale your content operations with precision and control.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Smooth Marquee Animation */
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
        
        /* Subtle Gradients & Utilities */
        .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(0,0,0,0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(0,0,0,0.05) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .text-gradient-primary {
            background: linear-gradient(135deg, #1e293b 0%, #4f46e5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="antialiased bg-white text-slate-900 selection:bg-indigo-100 selection:text-indigo-900">

    <!-- Navigation -->
    <nav x-data="{ mobileMenuOpen: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20)"
         :class="{ 'glass-nav shadow-sm': scrolled, 'bg-transparent': !scrolled }"
         class="fixed w-full z-50 transition-all duration-300 top-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
                    <div class="w-8 h-8 rounded bg-indigo-600 flex items-center justify-center text-white font-bold text-lg">S</div>
                    <span class="font-bold text-xl tracking-tight text-slate-900">SEO<span class="text-indigo-600">Master</span></span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#product" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Product</a>
                    <a href="#solutions" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Solutions</a>
                    <a href="#pricing" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Pricing</a>
                    
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4 ml-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-white bg-slate-900 hover:bg-slate-800 px-5 py-2.5 rounded-lg transition-all">Dashboard</a>
                            @else
                                <a href="#" class="text-sm font-medium text-slate-900 hover:text-indigo-600 transition-colors">Log in</a>
                                @if (Route::has('register'))
                                    <a href="#" class="text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 rounded-lg transition-all shadow-lg shadow-indigo-200">Try for Free</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex items-center md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-600 hover:text-slate-900 p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-cloak 
             x-transition.opacity
             class="md:hidden bg-white border-b border-slate-100 absolute w-full">
            <div class="px-4 pt-2 pb-6 space-y-2">
                <a href="#product" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-md">Product</a>
                <a href="#solutions" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-md">Solutions</a>
                <a href="#pricing" class="block px-3 py-2 text-base font-medium text-slate-600 hover:text-indigo-600 hover:bg-slate-50 rounded-md">Pricing</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 bg-grid-pattern -z-10 opacity-50"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-[600px] h-[600px] bg-indigo-50 rounded-full blur-3xl opacity-50 -z-10"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-[400px] h-[400px] bg-purple-50 rounded-full blur-3xl opacity-50 -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 text-xs font-bold uppercase tracking-wide mb-8">
                <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                v2.0 is now live
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 text-slate-900 leading-[1.1]">
                Create content that <br class="hidden md:block" />
                <span class="text-gradient-primary">ranks #1 on Google.</span>
            </h1>
            
            <p class="mt-6 max-w-2xl mx-auto text-xl text-slate-500 mb-10 leading-relaxed">
                The AI copilot for enterprise marketing teams. Generate SEO-optimized blog posts, landing pages, and marketing copy 10x faster.
            </p>
            
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-20">
                <a href="#" class="px-8 py-4 text-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl transition-all shadow-xl shadow-indigo-200 hover:-translate-y-1">
                    Start Free Trial
                </a>
                <a href="#demo" class="px-8 py-4 text-lg font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-all hover:border-slate-300">
                    View Demo
                </a>
            </div>

            <!-- Hero Dashboard Mockup -->
            <div class="relative max-w-6xl mx-auto">
                <div class="relative rounded-2xl bg-slate-900 p-2 shadow-2xl ring-1 ring-slate-900/10">
                    <div class="bg-slate-800 rounded-xl overflow-hidden aspect-[16/10] relative group">
                        <!-- Sidebar -->
                        <div class="absolute left-0 top-0 bottom-0 w-64 bg-slate-900 border-r border-slate-700 hidden md:block p-4">
                            <div class="space-y-4 mt-4">
                                <div class="h-8 bg-slate-800 rounded w-3/4"></div>
                                <div class="h-4 bg-slate-800 rounded w-1/2"></div>
                                <div class="h-4 bg-slate-800 rounded w-2/3"></div>
                                <div class="h-4 bg-slate-800 rounded w-1/2"></div>
                            </div>
                        </div>
                        <!-- Main Content Area -->
                        <div class="absolute left-0 md:left-64 top-0 right-0 bottom-0 bg-slate-50 p-8">
                            <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-sm p-8 h-full border border-slate-200">
                                <div class="h-10 bg-slate-100 rounded w-2/3 mb-6"></div>
                                <div class="space-y-3">
                                    <div class="h-4 bg-slate-100 rounded w-full"></div>
                                    <div class="h-4 bg-slate-100 rounded w-full"></div>
                                    <div class="h-4 bg-slate-100 rounded w-5/6"></div>
                                    <div class="h-4 bg-slate-100 rounded w-full"></div>
                                </div>
                                <div class="mt-8 p-4 bg-indigo-50 rounded-lg border border-indigo-100 flex gap-4 items-start">
                                    <div class="w-8 h-8 rounded-full bg-indigo-600 flex-shrink-0 flex items-center justify-center text-white text-xs">AI</div>
                                    <div>
                                        <div class="h-4 bg-indigo-200 rounded w-32 mb-2"></div>
                                        <div class="h-3 bg-indigo-100 rounded w-64"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Logo Marquee -->
    <div class="py-12 border-y border-slate-100 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 text-center">
            <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">Trusted by forward-thinking teams</p>
        </div>
        <div class="relative flex overflow-x-hidden group">
            <div class="animate-marquee whitespace-nowrap flex items-center gap-16 px-8">
                <!-- Logos (Duplicated for infinite scroll) -->
                <span class="text-2xl font-bold text-slate-300">ACME Corp</span>
                <span class="text-2xl font-bold text-slate-300">GlobalTech</span>
                <span class="text-2xl font-bold text-slate-300">Nebula</span>
                <span class="text-2xl font-bold text-slate-300">FoxRun</span>
                <span class="text-2xl font-bold text-slate-300">Circle</span>
                <span class="text-2xl font-bold text-slate-300">Trio</span>
                <span class="text-2xl font-bold text-slate-300">Kanba</span>
                <span class="text-2xl font-bold text-slate-300">Lume</span>
                <!-- Duplicate Set -->
                <span class="text-2xl font-bold text-slate-300">ACME Corp</span>
                <span class="text-2xl font-bold text-slate-300">GlobalTech</span>
                <span class="text-2xl font-bold text-slate-300">Nebula</span>
                <span class="text-2xl font-bold text-slate-300">FoxRun</span>
                <span class="text-2xl font-bold text-slate-300">Circle</span>
                <span class="text-2xl font-bold text-slate-300">Trio</span>
                <span class="text-2xl font-bold text-slate-300">Kanba</span>
                <span class="text-2xl font-bold text-slate-300">Lume</span>
            </div>
        </div>
    </div>

    <!-- Bento Grid Features -->
    <section id="product" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Everything you need to scale</h2>
                <p class="text-slate-500 text-lg">A complete suite of tools designed to replace your fragmented SEO stack.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 grid-rows-2 gap-6 h-auto md:h-[600px]">
                <!-- Large Feature (Left) -->
                <div class="md:col-span-2 md:row-span-2 bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center mb-6">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-3">AI Content Writer</h3>
                        <p class="text-slate-500 max-w-md">Generate high-quality, long-form content that reads like it was written by an industry expert. Our AI understands context, tone, and SEO requirements.</p>
                    </div>
                    <div class="absolute right-0 bottom-0 w-2/3 h-2/3 bg-slate-50 rounded-tl-3xl border-t border-l border-slate-100 p-6 translate-x-4 translate-y-4 group-hover:translate-x-2 group-hover:translate-y-2 transition-transform">
                        <!-- Mockup of text editor -->
                        <div class="space-y-3">
                            <div class="h-4 bg-slate-200 rounded w-full"></div>
                            <div class="h-4 bg-slate-200 rounded w-full"></div>
                            <div class="h-4 bg-slate-200 rounded w-3/4"></div>
                            <div class="h-4 bg-slate-200 rounded w-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Small Feature (Top Right) -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">SERP Analysis</h3>
                    <p class="text-slate-500 text-sm">Analyze top ranking pages to uncover their secrets.</p>
                </div>

                <!-- Small Feature (Bottom Right) -->
                <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-10 h-10 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Keyword Data</h3>
                    <p class="text-slate-500 text-sm">Real-time volume, difficulty, and intent data.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interactive Use Cases -->
    <section id="solutions" class="py-24 bg-white" x-data="{ tab: 'blog' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/3">
                    <h2 class="text-3xl font-bold text-slate-900 mb-6">Built for every content need</h2>
                    <div class="space-y-2">
                        <button @click="tab = 'blog'" :class="{ 'bg-slate-100 text-indigo-600': tab === 'blog', 'text-slate-600 hover:bg-slate-50': tab !== 'blog' }" class="w-full text-left px-6 py-4 rounded-xl font-semibold transition-all flex items-center justify-between group">
                            <span>Blog Posts</span>
                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" :class="{ 'opacity-100': tab === 'blog' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <button @click="tab = 'social'" :class="{ 'bg-slate-100 text-indigo-600': tab === 'social', 'text-slate-600 hover:bg-slate-50': tab !== 'social' }" class="w-full text-left px-6 py-4 rounded-xl font-semibold transition-all flex items-center justify-between group">
                            <span>Social Media</span>
                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" :class="{ 'opacity-100': tab === 'social' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        <button @click="tab = 'ads'" :class="{ 'bg-slate-100 text-indigo-600': tab === 'ads', 'text-slate-600 hover:bg-slate-50': tab !== 'ads' }" class="w-full text-left px-6 py-4 rounded-xl font-semibold transition-all flex items-center justify-between group">
                            <span>Ad Copy</span>
                            <svg class="w-5 h-5 opacity-0 group-hover:opacity-100 transition-opacity" :class="{ 'opacity-100': tab === 'ads' }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>
                
                <div class="w-full md:w-2/3">
                    <div class="bg-slate-900 rounded-2xl p-8 shadow-2xl min-h-[400px] flex items-center justify-center relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-20">
                            <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        
                        <div x-show="tab === 'blog'" x-transition.opacity class="text-white max-w-lg">
                            <div class="text-xs font-mono text-indigo-400 mb-4">AI Output: Blog Post</div>
                            <h3 class="text-2xl font-bold mb-4">10 Tips for Remote Work Success</h3>
                            <p class="text-slate-400 leading-relaxed">Remote work has become the new normal for millions of professionals worldwide. While it offers flexibility and freedom, it also presents unique challenges...</p>
                        </div>
                        
                        <div x-show="tab === 'social'" x-transition.opacity class="text-white max-w-lg" style="display: none;">
                            <div class="text-xs font-mono text-pink-400 mb-4">AI Output: LinkedIn Post</div>
                            <p class="text-lg font-medium mb-4">🚀 Excited to announce our new feature launch!</p>
                            <p class="text-slate-400 leading-relaxed">We've been working hard to bring you the best SEO automation tools on the market. Check out the link in bio to learn more. #SEO #Marketing #AI</p>
                        </div>
                        
                        <div x-show="tab === 'ads'" x-transition.opacity class="text-white max-w-lg" style="display: none;">
                            <div class="text-xs font-mono text-green-400 mb-4">AI Output: Google Ad</div>
                            <h3 class="text-xl font-bold mb-2">Boost Your Rankings Fast</h3>
                            <p class="text-slate-400 leading-relaxed">Stop guessing with your SEO. Use our AI-powered tools to find high-value keywords and create content that ranks. Start your free trial today.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing -->
    <section id="pricing" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Simple, transparent pricing</h2>
                <p class="text-slate-500">No credit card required to start.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <!-- Starter -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:border-indigo-200 transition-colors">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Starter</h3>
                    <div class="text-4xl font-bold text-slate-900 mb-6">$0<span class="text-lg text-slate-500 font-normal">/mo</span></div>
                    <p class="text-slate-500 text-sm mb-6">Perfect for individuals just getting started.</p>
                    <a href="#" class="block w-full py-3 rounded-lg border border-slate-200 text-slate-700 font-semibold text-center hover:bg-slate-50 transition-colors">Get Started</a>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center gap-3 text-slate-600 text-sm"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 5 AI Articles / month</li>
                        <li class="flex items-center gap-3 text-slate-600 text-sm"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Basic Keyword Research</li>
                    </ul>
                </div>

                <!-- Pro -->
                <div class="bg-slate-900 p-8 rounded-2xl border border-slate-800 shadow-xl relative transform md:-translate-y-4">
                    <div class="absolute top-0 right-0 bg-indigo-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg rounded-tr-lg">POPULAR</div>
                    <h3 class="text-lg font-bold text-white mb-2">Pro</h3>
                    <div class="text-4xl font-bold text-white mb-6">$49<span class="text-lg text-slate-400 font-normal">/mo</span></div>
                    <p class="text-slate-400 text-sm mb-6">For growing teams and agencies.</p>
                    <a href="#" class="block w-full py-3 rounded-lg bg-indigo-600 text-white font-semibold text-center hover:bg-indigo-500 transition-colors shadow-lg shadow-indigo-900/50">Start Free Trial</a>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center gap-3 text-slate-300 text-sm"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> 50 AI Articles / month</li>
                        <li class="flex items-center gap-3 text-slate-300 text-sm"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited Keyword Data</li>
                        <li class="flex items-center gap-3 text-slate-300 text-sm"><svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Content Audit Tool</li>
                    </ul>
                </div>

                <!-- Business -->
                <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:border-indigo-200 transition-colors">
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Business</h3>
                    <div class="text-4xl font-bold text-slate-900 mb-6">$199<span class="text-lg text-slate-500 font-normal">/mo</span></div>
                    <p class="text-slate-500 text-sm mb-6">For large organizations.</p>
                    <a href="#" class="block w-full py-3 rounded-lg border border-slate-200 text-slate-700 font-semibold text-center hover:bg-slate-50 transition-colors">Contact Sales</a>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center gap-3 text-slate-600 text-sm"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Unlimited Everything</li>
                        <li class="flex items-center gap-3 text-slate-600 text-sm"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> API Access</li>
                        <li class="flex items-center gap-3 text-slate-600 text-sm"><svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Dedicated Support</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <div class="col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-6 h-6 rounded bg-indigo-600 flex items-center justify-center text-white font-bold text-xs">S</div>
                        <span class="font-bold text-lg text-slate-900">SEO<span class="text-indigo-600">Master</span></span>
                    </div>
                    <p class="text-slate-500 text-sm max-w-xs">Empowering marketing teams with the next generation of AI tools.</p>
                </div>
                
                <div>
                    <h4 class="font-bold text-slate-900 mb-4 text-sm">Product</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Features</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">API</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 mb-4 text-sm">Company</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">About</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Careers</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-900 mb-4 text-sm">Legal</h4>
                    <ul class="space-y-2 text-sm text-slate-500">
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Privacy</a></li>
                        <li><a href="#" class="hover:text-indigo-600 transition-colors">Terms</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-400 text-sm">© {{ date('Y') }} SEO Master Inc.</p>
                <div class="flex gap-4">
                    <!-- Social Icons -->
                    <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#" class="text-slate-400 hover:text-slate-600 transition-colors"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
