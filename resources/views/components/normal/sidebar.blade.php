<div id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-white dark:bg-[#181818] border-r border-gray-200 dark:border-gray-800 flex flex-col z-50 transition-all duration-300 transform overflow-hidden group">
    
    <!-- Logo Section -->
   <div class="px-6 py-5 border-b border-zinc-200 dark:border-zinc-800 flex justify-between items-center whitespace-nowrap">
        <div class="flex items-center gap-2 logo-container transition-opacity duration-300">
            <img src="{{ asset('images/light_logo.png') }}" class="h-8 w-auto dark:hidden" alt="AiSeo Logo">
            <img src="{{ asset('images/dark_logo.png') }}" class="h-8 w-auto hidden dark:block" alt="AiSeo Logo">
        </div>
        <!-- Toggle Button -->
        <button onclick="toggleSidebar()" class="p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400">
            <span class="material-icons text-xl" id="toggleIcon">menu_open</span>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-1">

        <p class="px-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase sidebar-label whitespace-nowrap transition-opacity duration-300">Menu</p>

        <!-- Menu Item -->
        <a href="{{ route('normal.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all group-hover:justify-start">
            <span class="material-icons text-gray-700 dark:text-gray-400">home</span>
            <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Home</span>
        </a>

        <a href="{{ route('normal.chat') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
            <span class="material-icons text-gray-700 dark:text-gray-400">chat</span>
            <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Chat</span>
        </a>

        <a href="{{ route('normal.documents') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
            <span class="material-icons text-gray-700 dark:text-gray-400">auto_awesome_motion</span>
            <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Documents</span>
        </a>

        <a href="{{ route('normal.templates') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
            <span class="material-icons text-gray-700 dark:text-gray-400">folder_special</span>
            <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Templates</span>
        </a>

        <a href="{{ route('normal.outlines') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
            <span class="material-icons text-gray-700 dark:text-gray-400">format_list_bulleted</span>
            <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Outlines</span>
        </a>

        <a href="{{ route('normal.settings') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
            <span class="material-icons text-gray-700 dark:text-gray-400">settings</span>
            <span class="font-medium sidebar-text whitespace-nowrap transition-opacity duration-300">Settings</span>
        </a>

    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
        <form action="{{ route('normal.logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 rounded-lg font-medium transition-all">
                <span class="material-icons">logout</span>
                <span class="sidebar-text whitespace-nowrap transition-opacity duration-300">Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Push content -->
<div class="ml-64"></div>
