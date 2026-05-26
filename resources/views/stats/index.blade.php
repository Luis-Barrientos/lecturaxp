@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- Cabecera --}}
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900">Análisis de lectura</h2>
        <p class="mt-1 text-sm text-slate-500">Tendencias, patrones y datos sobre tu actividad lectora.</p>
    </div>

    {{-- SECCIÓN 1: Comparativa mes actual --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-8">
        {{-- XP Este Mes --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6 border-l-4 border-l-indigo-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600">XP Este Mes</h3>
                <div class="bg-indigo-50 rounded-full p-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold text-indigo-600">{{ number_format($thisMonthXP) }}</p>
            <p class="text-xs text-slate-500 mt-2">XP acumulados este mes</p>
        </div>

        {{-- Comparativa XP --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6 border-l-4 border-l-green-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600">Cambio mensual</h3>
                <div class="bg-green-50 rounded-full p-2">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            <p class="text-4xl font-bold {{ $xpDiff >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $xpDiff >= 0 ? '+' : '' }}{{ $xpDiff }}%
            </p>
            <p class="text-xs text-slate-500 mt-2">
                vs. mes anterior ({{ number_format($lastMonthXP) }} XP)
            </p>
        </div>
    </div>

    {{-- SECCIÓN 2: Estadísticas de lectura --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
        {{-- Velocidad lectura --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600">Velocidad</h3>
                <div class="bg-violet-50 rounded-full p-2">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-violet-600">{{ $avgPagesPerDay }}</p>
            <p class="text-xs text-slate-500 mt-2">págs/día promedio</p>
        </div>

        {{-- Páginas totales --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600">Páginas</h3>
                <div class="bg-amber-50 rounded-full p-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.75 10-10.747S17.5 6.253 12 6.253z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-amber-600">{{ number_format($totalPages) }}</p>
            <p class="text-xs text-slate-500 mt-2">páginas totales leídas</p>
        </div>

        {{-- Libros completados --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-600">Completados</h3>
                <div class="bg-emerald-50 rounded-full p-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-emerald-600">{{ $totalBooksCompleted }}</p>
            <p class="text-xs text-slate-500 mt-2">libros terminados</p>
        </div>
    </div>

    {{-- SECCIÓN 3: Gráficas lineales --}}
    <div class="grid grid-cols-1 gap-6 mb-8">
        
        {{-- Páginas por mes --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
            <h3 class="text-sm font-semibold text-slate-900 mb-4">📊 Páginas por mes</h3>
            @if($pagesByMonth->isEmpty())
                <p class="text-sm text-slate-400 text-center py-8">Sin datos.</p>
            @else
                <canvas id="pagesChart" height="80"></canvas>
            @endif
        </div>

        {{-- XP ganado por mes --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
            <h3 class="text-sm font-semibold text-slate-900 mb-4">⚡ XP ganado por mes</h3>
            @if($xpByMonth->isEmpty())
                <p class="text-sm text-slate-400 text-center py-8">Sin datos.</p>
            @else
                <canvas id="xpChart" height="80"></canvas>
            @endif
        </div>
    </div>

    <script>
        function initCharts() {
            const pagesData = @json($pagesByMonth);
            const xpData = @json($xpByMonth);
            
            // Gráfica de Páginas por mes
            if (Object.keys(pagesData).length > 0) {
                const pagesCtx = document.getElementById('pagesChart');
                if (pagesCtx) {
                    const pagesLabels = Object.keys(pagesData).map(month => {
                        const date = new Date(month + '-01');
                        return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
                    });
                    const pagesValues = Object.values(pagesData);
                    
                    new window.Chart(pagesCtx, {
                        type: 'line',
                        data: {
                            labels: pagesLabels,
                            datasets: [{
                                label: 'Páginas leídas',
                                data: pagesValues,
                                borderColor: '#4f46e5',
                                backgroundColor: 'rgba(79, 70, 229, 0.05)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointBackgroundColor: '#4f46e5',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(100, 116, 139, 0.1)',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#64748b',
                                        font: { size: 12 }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#64748b',
                                        font: { size: 12 }
                                    }
                                }
                            }
                        }
                    });
                }
            }
            
            // Gráfica de XP por mes
            if (Object.keys(xpData).length > 0) {
                const xpCtx = document.getElementById('xpChart');
                if (xpCtx) {
                    const xpLabels = Object.keys(xpData).map(month => {
                        const date = new Date(month + '-01');
                        return date.toLocaleDateString('es-ES', { month: 'short', year: 'numeric' });
                    });
                    const xpValues = Object.values(xpData);
                    
                    new window.Chart(xpCtx, {
                        type: 'line',
                        data: {
                            labels: xpLabels,
                            datasets: [{
                                label: 'XP ganado',
                                data: xpValues,
                                borderColor: '#a855f7',
                                backgroundColor: 'rgba(168, 85, 247, 0.05)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 5,
                                pointBackgroundColor: '#a855f7',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: { display: false }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(100, 116, 139, 0.1)',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#64748b',
                                        font: { size: 12 }
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#64748b',
                                        font: { size: 12 }
                                    }
                                }
                            }
                        }
                    });
                }
            }
        }
        
        // Ejecutar cuando el documento esté listo
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCharts);
        } else {
            initCharts();
        }
    </script>

    {{-- SECCIÓN 4: Géneros y Autores favoritos --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-8">
        {{-- Géneros favoritos --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
            <h3 class="text-sm font-semibold text-slate-900 mb-4">🎭 Géneros favoritos</h3>
            @if($favoriteGenres->isEmpty())
                <p class="text-sm text-slate-400 text-center py-8">Sin datos.</p>
            @else
                <div class="space-y-3">
                    @foreach($favoriteGenres as $item)
                        @php $percent = ($item['count'] / $favoriteGenres->first()['count']) * 100; @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-slate-800">{{ $item['genre'] ?? 'Sin género' }}</span>
                                <span class="text-slate-500">{{ $item['count'] }} sesiones</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Autores favoritos --}}
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
            <h3 class="text-sm font-semibold text-slate-900 mb-4">✍️ Autores más leídos</h3>
            @if($favoriteAuthors->isEmpty())
                <p class="text-sm text-slate-400 text-center py-8">Sin datos.</p>
            @else
                <div class="space-y-3">
                    @foreach($favoriteAuthors as $item)
                        @php $percent = ($item['count'] / $favoriteAuthors->first()['count']) * 100; @endphp
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="font-medium text-slate-800">{{ $item['author'] }}</span>
                                <span class="text-slate-500">{{ $item['count'] }} sesiones</span>
                            </div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-violet-500 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- SECCIÓN 5: Libros en progreso --}}
    @if($booksInProgress->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">📚 Libros en progreso</h3>
        <div class="space-y-4">
            @foreach($booksInProgress as $log)
                @php
                    $percent = $log->book->pages > 0
                        ? min(100, round(($log->pages_read_total / $log->book->pages) * 100))
                        : 0;
                @endphp
                <div class="p-4 bg-slate-50 rounded-lg">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-medium text-slate-800">{{ $log->book->title }}</span>
                        <span class="text-slate-500">{{ $log->pages_read_total }} / {{ $log->book->pages }} págs.</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full transition-all" style="width: {{ $percent }}%"></div>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">{{ $percent }}% completado</p>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection