@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900">Logros</h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ $earnedAchievements->count() }} de {{ $allAchievements->count() }} logros desbloqueados
        </p>
    </div>

    <style>
        @keyframes unlock-glow {
            0% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.7); }
            50% { box-shadow: 0 0 0 10px rgba(79, 70, 229, 0); }
            100% { box-shadow: 0 0 0 0 rgba(79, 70, 229, 0); }
        }
        
        @keyframes pulse-earned {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.95; }
        }
        
        .achievement-card.earned {
            animation: pulse-earned 2s ease-in-out infinite;
        }
        
        .achievement-card.earned:hover {
            animation: unlock-glow 1.5s ease-out;
        }
        
        .badge-new {
            animation: bounce 2s ease-in-out infinite;
        }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
    </style>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @foreach ($allAchievements as $achievement)
            @php
                $isEarned = $earnedAchievements->has($achievement->id);
                $earnedData = $isEarned ? $earnedAchievements->get($achievement->id) : null;
                $earnedAt = $isEarned ? $earnedData->pivot->earned_at : null;
                $isNew = $isEarned && $earnedAt && now()->diffInDays($earnedAt) <= 7;
                $daysAgo = $isEarned && $earnedAt ? now()->diffInDays($earnedAt) : null;
            @endphp

            <div class="achievement-card {{ $isEarned ? 'earned' : '' }} relative rounded-xl border p-5 transition-all duration-300 {{ $isEarned ? 'bg-gradient-to-br from-indigo-50 to-white border-indigo-200 shadow-md hover:shadow-lg' : 'bg-slate-50 border-slate-200 opacity-70 hover:opacity-80' }}">
                
                {{-- Badge "Nuevo" --}}
                @if ($isNew)
                <div class="badge-new absolute -top-3 -left-3 bg-amber-400 text-amber-900 text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                    ¡Nuevo!
                </div>
                @endif

                {{-- Badge de desbloqueo --}}
                @if ($isEarned)
                <div class="absolute top-3 right-3">
                    <div class="bg-indigo-600 text-white rounded-full w-8 h-8 flex items-center justify-center text-lg">
                        ✓
                    </div>
                </div>
                @endif

                {{-- Contenido principal --}}
                <div class="flex gap-3">
                    <span class="text-4xl flex-shrink-0">{{ $achievement->icon }}</span>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-900 text-sm">{{ $achievement->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1">{{ $achievement->description }}</p>
                    </div>
                </div>

                {{-- Footer con XP y estado --}}
                <div class="mt-4 pt-4 border-t {{ $isEarned ? 'border-indigo-100' : 'border-slate-200' }}">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600">
                            ⚡ {{ $achievement->xp_reward }} XP
                        </span>
                        @if ($isEarned)
                            <div class="text-right">
                                <p class="text-xs text-green-600 font-semibold">Desbloqueado</p>
                                @if ($daysAgo !== null)
                                    <p class="text-xs text-slate-400">
                                        @if ($daysAgo == 0)
                                            Hoy
                                        @elseif ($daysAgo == 1)
                                            Ayer
                                        @else
                                            Hace {{ $daysAgo }} días
                                        @endif
                                    </p>
                                @endif
                            </div>
                        @else
                            <span class="text-xs text-slate-400">Bloqueado</span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection