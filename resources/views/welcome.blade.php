<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home Brewing - Cerveza Artesanal</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    
    <!-- Vite Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .hero-banner {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.7)), url('{{ asset("images/banner_cerveza.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="antialiased bg-gray-900 text-white">
    <div class="relative min-h-screen hero-banner flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">
        
        <!-- Subtle Admin Link (Top Right) -->
        <div class="absolute top-6 right-6 flex gap-4 items-center">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-amber-500 hover:text-white transition-colors duration-200 border-b border-transparent hover:border-amber-500 pb-1 opacity-90 hover:opacity-100">
                    Ir al Panel ({{ Auth::user()->name }})
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-gray-300 hover:text-red-500 transition-colors duration-200 border-b border-transparent hover:border-red-500 pb-1 opacity-70 hover:opacity-100">
                        Cerrar Sesión
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors duration-200 border-b border-transparent hover:border-amber-500 pb-1 opacity-70 hover:opacity-100">
                    Iniciar Sesión
                </a>
            @endauth
        </div>

        <div class="text-center max-w-3xl mx-auto space-y-8 animate-fade-in-up">
            
            <!-- Logo if needed, or just text -->
            <div class="mb-8">
                <img src="{{ asset('images/cerveza_logo.png') }}" alt="Home Brewing Logo" class="h-32 w-auto mx-auto object-contain drop-shadow-2xl mb-6">
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-white drop-shadow-lg">
                    HOME <span class="text-amber-500">BREWING</span>
                </h1>
                <p class="mt-6 text-xl md:text-2xl text-gray-200 font-medium max-w-2xl mx-auto drop-shadow-md">
                    Auténtica cerveza artesanal, directa a tu puerta. <br class="hidden sm:block">Experimenta el verdadero sabor del lúpulo.
                </p>
            </div>

            <div class="mt-10 flex justify-center">
                <a href="/hacer-pedido" class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-gray-900 bg-amber-500 border border-transparent rounded-full shadow-lg hover:bg-amber-400 hover:scale-105 hover:shadow-amber-500/50 focus:outline-none focus:ring-4 focus:ring-amber-300 transition-all duration-300 ease-in-out">
                    Hacer Pedido Ahora
                    <svg class="w-5 h-5 ml-3 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
            
            <div class="mt-12 text-sm text-gray-400 font-medium tracking-wider uppercase">
                Stock Limitado &bull; Calidad Premium
            </div>
        </div>
        
    </div>
</body>
</html>
