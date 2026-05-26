@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                Editar sesión de lectura
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                {{ $book->title }} · {{ $book->author }}
            </p>
        </div>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl">
        <form action="{{ route('books.reading-logs.update', [$book, $readingLog]) }}" method="POST" class="px-4 py-6 sm:p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                <!-- Fecha -->
                <div class="sm:col-span-3">
                    <label for="date" class="block text-sm font-medium leading-6 text-slate-900">Fecha de la sesión</label>
                    <div class="mt-2">
                        <div class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 bg-slate-50 px-3.5">
                            {{ \Carbon\Carbon::parse($readingLog->date)->format('d/m/Y') }}
                        </div>
                        <p class="mt-2 text-xs text-slate-500">La fecha no puede modificarse después de creada la sesión.</p>
                    </div>
                </div>

                <!-- Páginas leídas -->
                <div class="sm:col-span-3">
                    <label for="pages_read" class="block text-sm font-medium leading-6 text-slate-900">
                        Páginas leídas <span class="text-slate-400">(máx. {{ $book->pages }})</span>
                    </label>
                    <div class="mt-2">
                        <input type="number" name="pages_read" id="pages_read"
                            value="{{ old('pages_read', $readingLog->pages_read) }}" min="1" max="{{ $book->pages }}" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('pages_read') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Comentarios -->
                <div class="sm:col-span-6">
                    <label for="comments" class="block text-sm font-medium leading-6 text-slate-900">
                        Comentarios <span class="text-slate-400">(opcional)</span>
                    </label>
                    <div class="mt-2">
                        <textarea name="comments" id="comments" rows="4"
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old('comments', $readingLog->comments) }}</textarea>
                        @error('comments') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-slate-900/10 pt-6">
                <a href="{{ route('books.reading-logs.index', $book) }}"
                    class="text-sm font-semibold leading-6 text-slate-900">Cancelar</a>
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                    Guardar sesión
                </button>
            </div>
        </form>
    </div>
</div>
@endsection