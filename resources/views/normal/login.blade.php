<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SEO Master</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
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
<body class="bg-white dark:bg-[#212121] text-black dark:text-white flex items-center justify-center min-h-screen">

    <div class="w-full max-w-[400px] px-5">
        <!-- Logo -->
        <div class="flex justify-center mb-10">
            <img
                src="{{ asset('images/light_logo.png') }}"
                class="h-12 w-auto dark:hidden"
                alt="Logo">
            <img
                src="{{ asset('images/dark_logo.png') }}"
                class="h-12 w-auto hidden dark:block"
                alt="Logo">
            </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-semibold mb-2">Welcome back</h1>
        </div>

        <!-- Form -->
        <form method="POST" action="{{ route('normal.login.post') }}" class="space-y-4">
            @csrf
            
            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-3 rounded text-sm text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <div>
                <input type="email" name="email" placeholder="Email address" 
                       class="w-full bg-white border border-[#565869] text-black dark:text-white rounded px-4 py-4 focus:outline-none focus:border-[#10a37f] placeholder-gray-400 transition-colors"
                       required>
            </div>

            <div>
                <input type="password" name="password" placeholder="Password"
                       class="w-full bg-white border border-[#565869] text-black dark:text-white rounded px-4 py-4 focus:outline-none focus:border-[#10a37f] placeholder-gray-400 transition-colors"
                       required>
            </div>

            <button type="submit" class="w-full bg-[#10a37f] hover:bg-[#1a7f64] text-white font-medium py-4 rounded transition-colors">
                Continue
            </button>
        </form>

        <!-- Footer / Links -->
        <div class="mt-6 text-center text-sm">
            <p class="text-gray-400 dark:text-gray-500  ">Forgot Password? <a href="#" class="text-[#10a37f] hover:underline dark:text-[#10a37f]">Contact Admin</a></p>
        </div>
    </div>

</body>
</html>
