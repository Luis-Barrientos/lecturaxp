@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl">
            Diario de lectura
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            {{ $book->title }} · {{ $book->author }}
        </p>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-x-3">
        <a href="{{ route('library.index') }}"
            class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">
            ← Volver a libros
        </a>
        <a href="{{ route('books.reading-logs.create', $book) }}"
            class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
            + Registrar sesión
        </a>
    </div>
</div>

{{-- Estadísticas rápidas --}}
<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 mb-8">
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600">{{ $logs->sum('pages_read') }}</p>
        <p class="text-xs text-slate-500 mt-1">Páginas leídas</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600">{{ $logs->count() }}</p>
        <p class="text-xs text-slate-500 mt-1">Sesiones</p>
    </div>
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-4 text-center">
        <p class="text-2xl font-bold text-indigo-600">
            {{ $book->pages > 0 ? round(($logs->sum('pages_read') / $book->pages) * 100) : 0 }}%
        </p>
        <p class="text-xs text-slate-500 mt-1">Progreso</p>
    </div>
</div>

{{-- Lista de sesiones --}}
<div class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-slate-900">Fecha</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Páginas</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Comentarios</th>
                <th class="relative py-3.5 pl-3 pr-4"><span class="sr-only">Acciones</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($logs as $log)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-slate-900">
                        {{ \Carbon\Carbon::parse($log->date)->format('d/m/Y') }}
                    </td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $log->pages_read }} págs.
                    </td>
                    <td class="px-3 py-4 text-sm text-slate-500">
                        {{ $log->comments ?? '—' }}
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm">
                        {{-- Botón Editar: lleva al formulario de edición de esta sesión --}}
                        <a href="{{ route('books.reading-logs.edit', [$book, $log]) }}"
                            class="text-indigo-600 hover:text-indigo-900 mr-4">
                            Editar
                        </a>

                        {{-- Botón Eliminar: envía un DELETE y pide confirmación --}}
                        <form action="{{ route('books.reading-logs.destroy', [$book, $log]) }}" method="POST"
                            class="inline"
                            onsubmit="return confirm('¿Eliminar esta sesión? Se revertirá el XP ganado.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-12 text-center text-sm text-slate-500">
                        No hay sesiones registradas todavía.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
