<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Hekimport - Sağlığınıza Açılan Modern Kapı</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🩺</text></svg>">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body {
                font-family: 'Inter', sans-serif;
                overflow-x: hidden;
            }
            .font-orbitron {
                font-family: 'Orbitron', sans-serif;
            }
            #background {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: -1;
                background: linear-gradient(45deg, #60a5fa, #6ee7b7, #f3f4f6, #60a5fa);
                background-size: 400%;
                animation: gradientFlow 12s ease infinite;
            }
            @keyframes gradientFlow {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
            .section {
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem;
                text-align: center;
                transition: all 0.8s ease;
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="background"></div>
        
        <div class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="fixed top-0 right-0 px-6 py-4 block">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-lg text-indigo-700 dark:text-indigo-400 font-semibold">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-lg text-indigo-700 dark:text-indigo-400 mr-4 font-semibold">Giriş Yap</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-lg text-indigo-700 dark:text-indigo-400 font-semibold">Kaydol</a>
                        @endif
                    @endauth
                </div>
            @endif

            <main>
                <section class="section">
                    <div>
                        <h1 class="font-orbitron text-5xl md:text-7xl font-bold text-white text-shadow mb-6">hekimport.com</h1>
                        <p class="text-xl md:text-2xl text-white text-shadow mb-8">Sağlığınıza Açılan Modern Kapı</p>
                        
                        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-white text-indigo-600 font-bold rounded-lg shadow-lg hover:bg-indigo-50 transition">
                                    Dashboard'a Git
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-8 py-3 bg-white text-indigo-600 font-bold rounded-lg shadow-lg hover:bg-indigo-50 transition">
                                    Hemen Başla
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-8 py-3 bg-transparent border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-indigo-600 transition">
                                        Kaydol
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </body>
</html>