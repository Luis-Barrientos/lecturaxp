<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'LecturaXP') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('Favicon_Lecturaxp.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased">
    <div class="min-h-full">
        <nav class="sticky top-0 z-40 border-b border-indigo-800 bg-indigo-700">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <img src="{{ asset('Logo_LecturaXP_Blanco.png') }}" alt="LecturaXP" class="h-12 w-auto">
                        </a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-1">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ Request::is('dashboard') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }} transition-colors">Dashboard</a>
                        <a href="{{ route('books.index') }}" class="px-3 py-2 text-sm font-medium rounded-md @if(Request::is('books') || (Request::is('books/*') && !Request::is('books/*/reading-logs*'))) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 hover:text-white @endif transition-colors">Catálogo</a>
                        <a href="{{ route('library.index') }}" class="px-3 py-2 text-sm font-medium rounded-md @if(Request::is('libreria*') || Request::is('books/*/reading-logs*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 hover:text-white @endif transition-colors">Mi librería</a>
                        <a href="{{ route('stats.index') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ Request::is('estadisticas*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }} transition-colors">Estadísticas</a>
                        <a href="{{ route('achievements.index') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ Request::is('logros*') ? 'bg-indigo-800 text-white' : 'text-indigo-100 hover:bg-indigo-600 hover:text-white' }} transition-colors">Logros</a>

                        {{-- Enlace al panel admin — solo visible para usuarios con rol 'admin' --}}
                        @auth
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.index') }}"
                                   class="px-3 py-2 text-sm font-semibold rounded-md {{ Request::is('admin*') ? 'bg-amber-500 text-white' : 'text-amber-300 hover:bg-amber-500 hover:text-white' }} transition-colors">
                                    ⚙ Admin
                                </a>
                            @endif
                        @endauth
                        </div>
                    </div>
                    <!-- Autenticación -->
                    <div class="flex items-center gap-x-4">
                        @auth
                            <span class="text-sm text-indigo-200">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-sm font-medium text-indigo-200 hover:text-white transition-colors">
                                    Cerrar sesión
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-3 py-2 text-sm font-medium text-indigo-200 hover:text-white transition-colors">Iniciar sesión</a>
                            <a href="{{ route('register') }}" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-50 transition-all">Registrarse</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>
        @if(isset($header))
            <header class="bg-white shadow-sm">
                <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                    <h1 class="text-lg font-semibold leading-6 text-slate-900">{{ $header }}</h1>
                </div>
            </header>
        @endif
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-4 rounded-md bg-green-50 p-4 border border-green-200">
                        <div class="flex">
                            <div class="flex-shrink-0 text-green-400">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 font-medium text-green-800">{{ session('success') }}</div>
                        </div>
                    </div>
                @endif

                {{-- Mensaje informativo azul (ej: libro ya completado) --}}
                @if(session('info'))
                    <div class="mb-4 rounded-md bg-blue-50 p-4 border border-blue-200">
                        <div class="flex">
                            <div class="flex-shrink-0 text-blue-400">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 font-medium text-blue-800">{{ session('info') }}</div>
                        </div>
                    </div>
                @endif

                @if(session('new_achievements'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                         class="fixed bottom-6 right-6 z-50 flex flex-col gap-3">
                        @foreach(session('new_achievements') as $logro)
                            <div class="bg-indigo-600 text-white rounded-xl shadow-lg px-5 py-4 flex items-center gap-4">
                                <span class="text-3xl">{{ $logro['icon'] }}</span>
                                <div>
                                    <p class="font-bold text-sm">¡Logro desbloqueado!</p>
                                    <p class="text-sm">{{ $logro['name'] }}</p>
                                    @if($logro['xp'] > 0)
                                        <p class="text-xs text-indigo-200">+{{ $logro['xp'] }} XP bonus</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>