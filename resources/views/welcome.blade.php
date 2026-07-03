<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'StudentPortal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased text-slate-800 bg-gradient-to-br from-slate-50 to-brand-light/30 min-h-screen flex flex-col">
        
        <!-- Navigation -->
        <nav class="bg-white/80 backdrop-blur-md border-b border-brand-light/50 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-brand mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="font-extrabold text-2xl tracking-tight text-brand-dark">Student<span class="text-brand">Portal</span></span>
                    </div>
                    <div>
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-semibold text-slate-600 hover:text-brand-dark transition duration-300">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="font-semibold text-slate-600 hover:text-brand-dark transition duration-300 mr-6">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white font-bold py-2.5 px-6 rounded-full shadow-md hover:shadow-lg hover:shadow-brand/30 transition-all duration-300 transform hover:-translate-y-0.5">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center pt-20 pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            
            <!-- Decorative Elements -->
            <div class="absolute top-10 left-10 w-72 h-72 bg-brand/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-0 right-10 w-72 h-72 bg-brand-light/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-brand-dark/10 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>

            <div class="max-w-5xl mx-auto text-center relative z-10">
                <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight mb-8">
                    Manage Tasks & <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand to-brand-dark">Attendance</span> with Ease
                </h1>
                <p class="mt-4 text-xl md:text-2xl text-slate-600 max-w-3xl mx-auto mb-10 leading-relaxed">
                    A modern, intuitive platform designed to streamline student workflows, track daily attendance, and manage assignments seamlessly in one unified workspace.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-brand to-brand-dark text-white text-lg font-bold py-4 px-10 rounded-full shadow-lg hover:shadow-xl hover:shadow-brand/40 transition-all duration-300 transform hover:-translate-y-1">
                            Get Started
                        </a>
                    @endif
                    <a href="{{ route('login') }}" class="bg-white/80 backdrop-blur text-brand-dark border border-brand/20 text-lg font-bold py-4 px-10 rounded-full shadow-sm hover:shadow-md hover:bg-white transition-all duration-300">
                        Sign In to Your Account
                    </a>
                </div>
            </div>
        </main>

        <!-- Features Showcase (Optional Minimal Section) -->
        <section class="py-20 bg-white/50 backdrop-blur-sm border-t border-brand-light/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="bg-white/80 p-8 rounded-2xl shadow-sm border border-brand-light/50 hover:-translate-y-1 transition-all duration-300 hover:shadow-md">
                        <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-brand-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Track Attendance</h3>
                        <p class="text-slate-600">Easily log your daily check-ins and submit leave requests directly from your personalized dashboard.</p>
                    </div>
                    <div class="bg-white/80 p-8 rounded-2xl shadow-sm border border-brand-light/50 hover:-translate-y-1 transition-all duration-300 hover:shadow-md">
                        <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-brand-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Submit Tasks</h3>
                        <p class="text-slate-600">View assigned coursework, submit responses, and upload file attachments for review by administrators.</p>
                    </div>
                    <div class="bg-white/80 p-8 rounded-2xl shadow-sm border border-brand-light/50 hover:-translate-y-1 transition-all duration-300 hover:shadow-md">
                        <div class="w-12 h-12 bg-brand-light rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-brand-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Generate Reports</h3>
                        <p class="text-slate-600">Administrators can generate comprehensive PDF reports for student attendance and task completion.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="py-8 bg-white/30 border-t border-brand-light/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500 font-medium">
                &copy; {{ date('Y') }} StudentPortal System. All rights reserved.
            </div>
        </footer>

        <style>
            @keyframes blob {
                0% { transform: translate(0px, 0px) scale(1); }
                33% { transform: translate(30px, -50px) scale(1.1); }
                66% { transform: translate(-20px, 20px) scale(0.9); }
                100% { transform: translate(0px, 0px) scale(1); }
            }
            .animate-blob {
                animation: blob 7s infinite;
            }
            .animation-delay-2000 {
                animation-delay: 2s;
            }
            .animation-delay-4000 {
                animation-delay: 4s;
            }
        </style>
    </body>
</html>
