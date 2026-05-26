@extends('layouts.admin')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold text-slate-900">Gestión de logros</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $achievements->count() }} logros en la plataforma.</p>
    </div>
    <a href="{{ route('admin.achievements.create') }}"
        class="mt-4 sm:mt-0 block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
        + Nuevo logro
    </a>
</div>

<div class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-slate-900">Logro</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Condición</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Valor</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">XP recompensa</th>
                <th class="relative py-3.5 pl-3 pr-6"><span class="sr-only">Acciones</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($achievements as $achievement)
                <tr class="hover:bg-slate-50 transition-colors">

                    {{-- Icono, nombre y descripción --}}
                    <td class="py-4 pl-6 pr-3 text-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $achievement->icon }}</span>
                            <div>
                                <p class="font-medium text-slate-900">{{ $achievement->name }}</p>
                                <p class="text-xs text-slate-400">{{ $achievement->description }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Tipo de condición con badge de color --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                            {{ $achievement->condition_type }}
                        </span>
                    </td>

                    {{-- Valor numérico de la condición --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $achievement->condition_value }}
                    </td>

                    {{-- XP de recompensa --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-indigo-600">
                        +{{ $achievement->xp_reward }} XP
                    </td>

                    {{-- Botones editar y eliminar --}}
                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm">
                        <div class="flex justify-end gap-x-3">
                            <a href="{{ route('admin.achievements.edit', $achievement) }}"
                                class="text-indigo-600 hover:text-indigo-900">Editar</a>

                            <form action="{{ route('admin.achievements.destroy', $achievement) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar este logro? Los usuarios que lo tenían lo perderán.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </form>
                        </div>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-sm text-slate-500">
                        No hay logros creados todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection