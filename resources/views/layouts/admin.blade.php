<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name', 'LecturaXP') }}</title>
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

        {{-- Barra de navegación del admin — color oscuro para diferenciarla del panel de usuario --}}
        <nav class="sticky top-0 z-40 bg-slate-900 border-b border-slate-700">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">

                    {{-- Logo + etiqueta Admin --}}
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.index') }}">
                            <img src="{{ asset('Logo_Lecturaxp.png') }}" alt="LecturaXP" class="h-10 w-auto brightness-0 invert">
                        </a>
                        <span class="text-xs font-semibold text-indigo-400 bg-indigo-900 px-2 py-0.5 rounded-full">
                            ADMIN
                        </span>
                    </div>

                    {{-- Navegación del panel --}}
                    <div class="flex items-baseline space-x-2">
                        <a href="{{ route('admin.index') }}"
                            class="px-3 py-2 text-sm font-medium rounded-md {{ Request::is('admin') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }} transition-colors">
                            Panel
                        </a>
                        <a href="{{ route('admin.users') }}"
                            class="px-3 py-2 text-sm font-medium rounded-md {{ Request::is('admin/usuarios*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }} transition-colors">
                            Usuarios
                        </a>
                        <a href="{{ route('admin.books') }}"
                            class="px-3 py-2 text-sm font-medium rounded-md {{ Request::is('admin/libros*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }} transition-colors">
                            Libros
                        </a>
                        <a href="{{ route('admin.achievements') }}"
                            class="px-3 py-2 text-sm font-medium rounded-md {{ Request::is('admin/logros*') ? 'bg-slate-700 text-white' : 'text-slate-300 hover:text-white hover:bg-slate-700' }} transition-colors">
                            Logros
                        </a>

                        <a href="{{ route('admin.books.import-form') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium {{ Request::is('admin/libros/importar*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-600' }}">
                            📚 Importar libros
                        </a>
                    </div>

                    {{-- Acciones de sesión --}}
                    <div class="flex items-center gap-x-4">
                        {{-- Enlace para volver al panel de usuario --}}
                        <a href="{{ route('dashboard') }}"
                            class="text-sm text-slate-400 hover:text-white transition-colors">
                            ← Volver a la app
                        </a>
                        <span class="text-sm text-slate-400">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-slate-400 hover:text-red-400 transition-colors">
                                Cerrar sesión
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </nav>

        {{-- Mensajes de éxito y error --}}
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

                @if(session('error'))
                    <div class="mb-4 rounded-md bg-red-50 p-4 border border-red-200">
                        <div class="flex">
                            <div class="flex-shrink-0 text-red-400">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9v4a1 1 0 102 0V9a1 1 0 10-2 0zm0-4a1 1 0 112 0 1 1 0 01-2 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 font-medium text-red-800">{{ session('error') }}</div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

    </div>
</body>
</html>