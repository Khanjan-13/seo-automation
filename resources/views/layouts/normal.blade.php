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

    <div class="ml-64 h-screen flex flex-col bg-white dark:bg-[#212121] transition-colors duration-200">

        <!-- Main Content Area -->
        <div class="flex-1 overflow-y-auto flex flex-col">
            @yield('chat')
        </div>

    </div>

</body>
</html>
