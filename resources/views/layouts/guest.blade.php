<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-100">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LecturaXP') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('Favicon_Lecturaxp.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
       <body class="h-full text-slate-900 antialiased">
        <div class="min-h-full flex flex-col">

                          <!-- Sección superior indigo -->
<div class="relative bg-gradient-to-br from-indigo-800 via-indigo-700 to-indigo-500 flex flex-col items-center justify-end pt-20 pb-0">
                <!-- Círculos decorativos de fondo -->
                <div class="absolute -top-16 -left-16 w-64 h-64 bg-white opacity-5 rounded-full"></div>
                <div class="absolute top-4 -right-10 w-48 h-48 bg-white opacity-5 rounded-full"></div>
                <div class="absolute bottom-8 left-16 w-32 h-32 bg-amber-400 opacity-10 rounded-full"></div>
                <div class="absolute top-2 left-1/2 w-20 h-20 bg-white opacity-5 rounded-full"></div>

                <h1 class="relative text-5xl font-bold text-white tracking-tight mb-3">LecturaXP</h1>
                <p class="relative text-amber-400 text-sm font-semibold tracking-widest uppercase mb-12">Tu plataforma de lecturas</p>
                
                <!-- Barra separadora con contador de usuarios -->
                <div class="z-10 flex items-center gap-3 bg-white rounded-full px-6 py-3 shadow-xl border border-indigo-100 -mb-6">
                    <span class="text-lg">👥</span>
                    <span class="text-sm font-semibold text-slate-700">
                        <span class="text-indigo-600 font-bold">{{ \App\Models\User::count() }}</span>
                        lectores en la comunidad
                    </span>
                </div>
            </div>

            <!-- Sección inferior slate -->
            <div class="flex-1 bg-slate-100 flex flex-col items-center justify-start py-8 px-4">

                <!-- Card del formulario -->
                <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden mb-6">
                    {{ $slot }}
                </div>

                <!-- Bloque inferior dinámico según la vista -->
                <div class="w-full max-w-md">
                    {{ $footer ?? '' }}
                </div>

            </div>
        </div>
    </body>
</html>
