@extends('layouts.app')

@section('content')

    {{-- Cabecera de bienvenida --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-slate-900 sm:text-3xl">¡Bienvenido, {{ $user->name }}!</h2>
        <p class="mt-1 text-sm text-slate-500">Aquí tienes tu resumen de actividad lectora.</p>
    </div>

    {{-- Tarjetas de estadísticas --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">

        {{-- Nivel y XP --}}
        <div class="bg-white rounded-xl shadow-md border border-slate-200 border-l-4 border-l-indigo-500 p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Nivel actual</p>
                <div class="rounded-lg bg-indigo-50 p-2">
                    <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-3xl font-bold text-indigo-600">{{ $user->current_level }}</p>
            <div class="mt-3">
                <div class="flex justify-between text-xs text-slate-500 mb-1">
                    <span>{{ $user->total_xp }} XP</span>
                    <span>{{ $xpNextLevel }} XP</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2">
                    {{-- Barra de progreso --}}
                    <div class="bg-indigo-600 h-2 rounded-full transition-all"
                         style="width: {{ min($xpProgress, 100) }}%"></div>
                </div>
                <p class="mt-1 text-xs text-slate-400 text-right">{{ $xpProgress }}% hacia nivel {{ $user->current_level + 1 }}</p>
            </div>
        </div>

        {{-- Racha --}}
        <div class="bg-white rounded-xl shadow-md border border-slate-200 border-l-4 border-l-amber-400 p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Racha actual</p>
                <div class="rounded-lg bg-amber-50 p-2">
                    <svg class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0112 21 8.25 8.25 0 016.038 7.048 8.287 8.287 0 009 9.6a8.983 8.983 0 013.361-6.867 8.21 8.21 0 003 2.48z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 00.495-7.467 5.99 5.99 0 00-1.925 3.546 5.974 5.974 0 01-2.133-1A3.75 3.75 0 0012 18z" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-3xl font-bold text-amber-500">{{ $user->streak_days }} días</p>
            <p class="mt-3 text-xs text-slate-400">
                @if($user->streak_days >= 3)
                    ¡Bonus de XP activo! (+10%)
                @else
                    {{ 3 - $user->streak_days }} días más para activar el bonus
                @endif
            </p>
        </div>

        {{-- XP Total --}}
        <div class="bg-white rounded-xl shadow-md border border-slate-200 border-l-4 border-l-violet-500 p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">XP Total</p>
                <div class="rounded-lg bg-violet-50 p-2">
                    <svg class="h-5 w-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 text-3xl font-bold text-violet-600">{{ number_format($user->total_xp) }}</p>
            <p class="mt-3 text-xs text-slate-400">Puntos acumulados desde el inicio</p>
        </div>

    </div>

    {{-- Actividad reciente --}}
    <div class="bg-white shadow-sm border border-slate-200 sm:rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200">
            <h3 class="text-base font-semibold text-slate-900">Actividad reciente</h3>
        </div>
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-slate-900">Libro</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Fecha</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Páginas</th>
                    <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">XP ganado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($recentLogs as $log)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-slate-900">
                            {{ $log->book->title }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                            {{ \Carbon\Carbon::parse($log->date)->format('d/m/Y') }}
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                            {{ $log->pages_read }} págs.
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-indigo-600">
                            +{{ $log->xp_earned }} XP
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-sm text-slate-500">
                            Aún no hay sesiones registradas. ¡Empieza a leer!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection