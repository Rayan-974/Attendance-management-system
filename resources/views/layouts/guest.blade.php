<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-slate-50 to-brand-light/30 relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-10 left-10 w-72 h-72 bg-brand/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
            <div class="absolute bottom-10 right-10 w-72 h-72 bg-brand-light/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
            
            <div class="z-10 relative">
                <a href="/" class="flex items-center">
                    <svg class="w-10 h-10 text-brand mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <span class="font-extrabold text-3xl tracking-tight text-brand-dark">Student<span class="text-brand">Portal</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white/80 backdrop-blur-md shadow-xl sm:rounded-2xl border border-white/50 overflow-hidden z-10 relative">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
