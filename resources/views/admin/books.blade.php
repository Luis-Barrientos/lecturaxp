@extends('layouts.admin')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-8">
    <div>
        <h2class="text-2xl font-bold text-slate-900">Gestión de libros</h2>
        <p class="mt-1 text-sm text-slate-500">{{ $books->count() }} libros en la plataforma.</p>
    </div>
    <a href="{{ route('books.create') }}"class="mt-4 sm:mt-0 block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all"> 
        + Nuevo libro </a>
</div>

<div class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
        <thead class="bg-slate-50">
            <tr>
                <th class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-slate-900">Título</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Autor</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Páginas</th>
                <th class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Añadido</th>
                <th class="relative py-3.5 pl-3 pr-6"><span class="sr-only">Acciones</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
            @forelse($books as $book)
                <tr class="hover:bg-slate-50 transition-colors">

                    {{-- Título e ISBN --}}
                    <td class="py-4 pl-6 pr-3 text-sm">
                        <p class="font-medium text-slate-900">{{ $book->title }}</p>
                        <p class="text-xs text-slate-400">ISBN: {{ $book->isbn }}</p>
                    </td>

                    {{-- Autor --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $book->author }}
                    </td>

                    {{-- Páginas --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $book->pages }} págs.
                    </td>

                    {{-- Fecha en que se añadió el libro --}}
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                        {{ $book->created_at->format('d/m/Y') }}
                    </td>

                    {{-- Botones: Editar y Eliminar --}}
                    <td class="whitespace-nowrap px-3 py-4 text-right text-sm space-x-3">
                        <a href="{{ route('books.edit', $book) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                            Editar
                        </a>
                        <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="inline"
                            onsubmit="return confirm('¿Eliminar este libro? Se borrarán también todas sus sesiones de lectura.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                Eliminar
                            </button>
                        </form>
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-sm text-slate-500">
                        No hay libros en la plataforma.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection