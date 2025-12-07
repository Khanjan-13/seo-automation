<div class="fixed left-0 top-0 h-screen w-64 bg-white dark:bg-[#181818] border-r border-gray-200 dark:border-gray-800 flex flex-col z-50 transition-colors duration-200">

    <!-- Logo Section -->
    <div class="p-6 border-b border-gray-100 dark:border-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-gray-900 dark:bg-white rounded-xl flex items-center justify-center">
                <span class="material-icons text-white dark:text-gray-900 text-xl">admin_panel_settings</span>
            </div>
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-white tracking-tight">Admin</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Dashboard</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto p-4 space-y-1">

        <p class="px-4 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Menu</p>

        <!-- Menu Items -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
            <span class="material-icons text-gray-700 dark:text-gray-400">dashboard</span>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="{{ route('admin.users.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
            <span class="material-icons text-gray-700 dark:text-gray-400">people</span>
            <span class="font-medium">Manage Users</span>
        </a>

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-800 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
            <span class="material-icons text-gray-700 dark:text-gray-400">settings</span>
            <span class="font-medium">Settings</span>
        </a>

    </nav>

    <!-- Logout -->
    <div class="p-4 border-t border-gray-100 dark:border-gray-800">
        <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-900 dark:text-white py-3 px-4 rounded-lg font-medium transition-all">
                <span class="material-icons">logout</span>
                <span>Logout</span>
            </button>
        </form>
    </div>
</div>

<!-- Push content -->
<div class="ml-64"></div>
