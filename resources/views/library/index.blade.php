@extends('layouts.app')

@section('content')
    <div x-data="libraryAccordion()" class="space-y-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Mi librería
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Los libros que estás leyendo o has completado.
                </p>
            </div>

            {{-- Enlace al catálogo para que el usuario pueda añadir más libros --}}
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('books.index') }}"
                   class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                    + Explorar catálogo
                </a>
            </div>
        </div>

        {{-- Tarjetas estadísticas --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            {{-- Libros leyendo --}}
            <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Libros leyendo</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $booksReading }}</p>
                    </div>
                    <div class="bg-indigo-50 rounded-full p-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.75 10-10.747S17.5 6.253 12 6.253z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Libros completados --}}
            <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Completados</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $booksCompleted }}</p>
                    </div>
                    <div class="bg-green-50 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- XP ganado --}}
            <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">XP ganado</p>
                        <p class="text-3xl font-bold text-violet-600 mt-2">{{ $totalXP }}</p>
                    </div>
                    <div class="bg-violet-50 rounded-full p-3">
                        <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>


        {{-- Botones para alternar vistas --}}
        <div class="flex gap-2">
            <button @click="setViewMode('tabla')"
                    :class="viewMode === 'tabla' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-700'"
                    class="px-4 py-2 rounded-md font-medium transition-colors hover:opacity-90">
                 Tabla
            </button>
            <button @click="setViewMode('grid')"
                    :class="viewMode === 'grid' ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-700'"
                    class="px-4 py-2 rounded-md font-medium transition-colors hover:opacity-90">
                 Grid
            </button>
        </div>
         <div class="flow-root" x-show="viewMode === 'tabla'">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg bg-white">
                        <table class="min-w-full divide-y divide-slate-300">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-slate-900 sm:pl-6">Libro</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Progreso</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Estado</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Acciones</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 bg-white">
                                @forelse($books as $book)
                                    {{-- Calculamos páginas leídas del usuario --}}
                                    @php
                                        $pagesRead = $book->readingLogs
                                            ->where('user_id', auth()->id())
                                            ->sum('pages_read');
                                        $progressPercent = min(($pagesRead / $book->pages) * 100, 100);
                                    @endphp

                                    <tr class="hover:bg-slate-50 transition-colors">
                                        {{-- Libro: Portada + Info --}}
                                        <td class="py-4 pl-4 pr-3 text-sm sm:pl-6">
                                            <div class="flex items-start gap-4">
                                                {{-- Thumbnail de portada --}}
                                                <div class="flex-shrink-0">
                                                    @if($book->cover_url)
                                                        <img src="{{ $book->cover_url }}" 
                                                             alt="{{ $book->title }}"
                                                             class="h-20 w-14 object-cover rounded shadow-sm">
                                                    @else
                                                        <div class="h-20 w-14 bg-gradient-to-br from-indigo-500 to-violet-600 rounded shadow-sm flex items-center justify-center">
                                                            <span class="text-white text-xs font-bold">{{ substr($book->title, 0, 1) }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Información del libro --}}
                                                <div class="min-w-0 flex-1">
                                                    <h3 class="text-sm font-medium text-slate-900 truncate">{{ $book->title }}</h3>
                                                    <p class="text-xs text-slate-500">{{ $book->author }}</p>
                                                    <p class="text-xs text-slate-500">{{ $book->pages }} págs.</p>

                                                    {{-- Botón para expandir detalles --}}
                                                    <button @click="toggle({{ $book->id }})"
                                                            class="mt-1 text-xs text-indigo-600 hover:text-indigo-700 font-medium focus:outline-none">
                                                        <span x-show="!expanded[{{ $book->id }}]">+ Más detalles</span>
                                                        <span x-show="expanded[{{ $book->id }}]">- Menos detalles</span>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Acordeón: Detalles expandibles --}}
                                            <div x-show="expanded[{{ $book->id }}]" 
                                                 x-transition
                                                 class="mt-3 pt-3 border-t border-slate-200 space-y-2 text-xs">
                                                @if($book->genre)
                                                    <div>
                                                        <span class="font-medium text-slate-700">Género:</span>
                                                        <span class="text-slate-600">{{ $book->genre }}</span>
                                                    </div>
                                                @endif
                                                @if($book->description)
                                                    <div>
                                                        <span class="font-medium text-slate-700">Descripción:</span>
                                                        <p class="text-slate-600 line-clamp-2">{{ $book->description }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Progreso de lectura --}}
                                        <td class="px-3 py-4">
                                            <div class="space-y-1">
                                                {{-- Barra de progreso --}}
                                                <div class="w-32 bg-slate-200 rounded-full h-2">
                                                    <div class="bg-indigo-600 h-2 rounded-full transition-all" 
                                                         style="width: {{ $progressPercent }}%"></div>
                                                </div>
                                                {{-- Texto de progreso --}}
                                                <p class="text-xs text-slate-600">
                                                    {{ $pagesRead }} / {{ $book->pages }} págs.
                                                </p>
                                            </div>
                                        </td>

                                        {{-- Estado --}}
                                        <td class="px-3 py-4 text-sm whitespace-nowrap">
                                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                                {{ $book->pivot->status === 'completado' ? 'bg-green-100 text-green-700' : 'bg-indigo-50 text-indigo-700' }}">
                                                {{ $book->pivot->status }}
                                            </span>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <div class="flex justify-end gap-x-3">
                                                {{-- Enlace al diario de lectura de este libro --}}
                                                <a href="{{ route('books.reading-logs.index', $book) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                                    Ver diario
                                                </a>

                                                {{-- Quitar el libro de la librería personal (no borra el libro del catálogo) --}}
                                                <form action="{{ route('library.destroy', $book) }}" method="POST"
                                                    onsubmit="return confirm('¿Quitar este libro de tu librería?')"
                                                    class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                                        Quitar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-12 text-center text-sm text-slate-500">
                                            Tu librería está vacía. <a href="{{ route('books.index') }}" class="text-indigo-600 hover:underline">Explora el catálogo</a> para añadir libros.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

                {{-- VISTA GRID --}}
        <div x-show="viewMode === 'grid'" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($books as $book)
                {{-- Calculamos páginas leídas del usuario --}}
                @php
                    $pagesRead = $book->readingLogs
                        ->where('user_id', auth()->id())
                        ->sum('pages_read');
                    $progressPercent = min(($pagesRead / $book->pages) * 100, 100);
                @endphp

                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow border border-slate-200">
                    {{-- Portada --}}
                    <div class="aspect-[2/3] bg-slate-100 overflow-hidden">
                        @if($book->cover_url)
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full w-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                                <span class="text-white text-4xl font-bold">{{ substr($book->title, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div class="p-4 space-y-3">
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $book->title }}</h3>
                            <p class="text-xs text-slate-500">{{ $book->author }}</p>
                        </div>

                        {{-- Progreso --}}
                        <div>
                            <div class="w-full bg-slate-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                            </div>
                            <p class="text-xs text-slate-600 mt-1">{{ $pagesRead }} / {{ $book->pages }} págs.</p>
                        </div>

                        {{-- Estado + Género --}}
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium
                                {{ $book->pivot->status === 'completado' ? 'bg-green-100 text-green-700' : 'bg-indigo-50 text-indigo-700' }}">
                                {{ $book->pivot->status }}
                            </span>
                            @if($book->genre)
                                <span class="text-xs bg-slate-100 text-slate-700 px-2 py-1 rounded">{{ $book->genre }}</span>
                            @endif
                        </div>

                        {{-- Acciones --}}
                        <div class="pt-2 border-t border-slate-200 flex gap-x-2">
                            <a href="{{ route('books.reading-logs.index', $book) }}" 
                               class="flex-1 text-center text-xs text-indigo-600 hover:text-indigo-900 font-medium">
                                Ver diario
                            </a>
                            <form action="{{ route('library.destroy', $book) }}" method="POST" class="flex-1"
                                onsubmit="return confirm('¿Quitar este libro de tu librería?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-xs text-red-600 hover:text-red-900 font-medium">
                                    Quitar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-sm text-slate-500">
                    Tu librería está vacía. <a href="{{ route('books.index') }}" class="text-indigo-600 hover:underline">Explora el catálogo</a> para añadir libros.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Script Alpine para el acordeón --}}
    <script>
        function libraryAccordion() {
            return {
                viewMode: 'tabla',  // 'tabla' o 'grid'
                expanded: {
                    @foreach($books as $book)
                        {{ $book->id }}: false{{ !$loop->last ? ',' : '' }}
                    @endforeach
                },
                toggle(bookId) {
                    this.expanded[bookId] = !this.expanded[bookId];
                },
                setViewMode(mode) {
                    this.viewMode = mode;
                }
            }
        }
</script>
@endsection