<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - ChatAI</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Poppins', sans-serif; }
    </style>
    <script>
        // Apply system theme preference immediately
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            if (event.matches) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        });
    </script>
</head>

<body class="bg-white dark:bg-[#212121] transition-colors duration-200">

    @php
        $chats = $chats ?? collect();
    @endphp

    <x-normal.sidebar :chats="$chats" />

    <div id="mainContent" class="ml-64 h-screen flex flex-col bg-white dark:bg-[#212121] transition-all duration-300">

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto flex flex-col">
            @yield('chat')
        </div>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleIcon = document.getElementById('toggleIcon');
            const texts = document.querySelectorAll('.sidebar-text');
            const labels = document.querySelectorAll('.sidebar-label');
            const logoContainer = document.querySelector('.logo-container');
            
            // Check current state (if w-20, it is collapsed/mini)
            const isMini = sidebar.classList.contains('w-20');

            if (isMini) {
                // Expand
                sidebar.classList.remove('w-20');
                sidebar.classList.add('w-64');
                
                mainContent.classList.remove('ml-20');
                mainContent.classList.add('ml-64');

                // Update Icon
                toggleIcon.innerText = 'menu_open';

                // Show Text
                texts.forEach(el => el.classList.remove('opacity-0', 'hidden'));
                labels.forEach(el => el.classList.remove('opacity-0', 'hidden'));
                if(logoContainer) logoContainer.classList.remove('opacity-0', 'invisible');

                localStorage.setItem('sidebarState', 'expanded');
            } else {
                // Collapse to Mini
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-20');

                // Update Icon
                toggleIcon.innerText = 'menu'; // or chevron_right

                // Hide Text with transition
                texts.forEach(el => el.classList.add('opacity-0', 'hidden'));
                labels.forEach(el => el.classList.add('opacity-0', 'hidden'));
                if(logoContainer) logoContainer.classList.add('opacity-0', 'invisible');

                localStorage.setItem('sidebarState', 'mini');
            }
        }

        // Initialize state on load
        document.addEventListener('DOMContentLoaded', () => {
            const state = localStorage.getItem('sidebarState');
            if (state === 'mini') {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('mainContent');
                const toggleIcon = document.getElementById('toggleIcon');
                const texts = document.querySelectorAll('.sidebar-text');
                const labels = document.querySelectorAll('.sidebar-label');
                const logoContainer = document.querySelector('.logo-container');

                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                
                mainContent.classList.remove('ml-64');
                mainContent.classList.add('ml-20');

                toggleIcon.innerText = 'menu';

                texts.forEach(el => el.classList.add('opacity-0', 'hidden'));
                labels.forEach(el => el.classList.add('opacity-0', 'hidden'));
                if(logoContainer) logoContainer.classList.add('opacity-0', 'invisible');
            }
        });
    </script>

</body>
</html>
