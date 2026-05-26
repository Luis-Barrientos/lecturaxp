@extends('layouts.app')

@section('content')

    {{-- Cabecera --}}
    <div class="sm:flex sm:items-center sm:justify-between mb-8">
        <div>
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:text-3xl sm:tracking-tight">
                Catálogo de libros
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Explora todos los libros disponibles y añádelos a tu librería.
            </p>
        </div>

        {{-- Solo el admin puede añadir libros al catálogo --}}
        @if(auth()->user()->role === 'admin')
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('books.create') }}"
                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                    + Añadir al catálogo
                </a>
            </div>
        @endif
    </div>
    {{-- ========== BARRA DE BÚSQUEDA Y FILTROS ========== --}}
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6 mb-8">
        
        {{-- Título de la sección --}}
        <h3 class="text-lg font-semibold text-slate-900 mb-4">Busca libros</h3>
        
        {{-- Formulario GET para buscar/filtrar --}}
        {{-- GET es importante: la URL se actualiza con los parámetros, es bookmarkable --}}
        <form method="GET" action="{{ route('books.index') }}" class="space-y-4">
            
            {{-- FILA 1: Búsqueda y Género (responsive: columna en móvil, row en desktop) --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                
                {{-- CAMPO 1: Búsqueda por título o autor --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-slate-900 mb-1">
                        Búsqueda
                    </label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        placeholder="Título o autor..."
                        value="{{ $filters['search'] }}"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    />
                </div>

                {{-- CAMPO 2: Filtro por género --}}
                <div>
                    <label for="genre" class="block text-sm font-medium text-slate-900 mb-1">
                        Género
                    </label>
                    <select 
                        id="genre" 
                        name="genre"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    >
                        {{-- Primera opción: "Todos los géneros" (value vacío = sin filtro) --}}
                        <option value="">Todos los géneros</option>
                        
                        {{-- Iterar sobre los géneros obtenidos del controlador --}}
                        @foreach($genres as $genreOption)
                            <option 
                                value="{{ $genreOption }}"
                                {{-- Si es el género actual en el filtro, marcar como selected --}}
                                {{ $filters['genre'] === $genreOption ? 'selected' : '' }}
                            >
                                {{ $genreOption }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- CAMPO 3: Ordenación --}}
                <div>
                    <label for="sort" class="block text-sm font-medium text-slate-900 mb-1">
                        Ordenar por
                    </label>
                    <select 
                        id="sort" 
                        name="sort"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    >
                        <option value="title_asc" {{ $filters['sort'] === 'title_asc' ? 'selected' : '' }}>Título (A → Z)</option>
                        <option value="title_desc" {{ $filters['sort'] === 'title_desc' ? 'selected' : '' }}>Título (Z → A)</option>
                        <option value="popular_desc" {{ $filters['sort'] === 'popular_desc' ? 'selected' : '' }}>Más popular</option>
                        <option value="popular_asc" {{ $filters['sort'] === 'popular_asc' ? 'selected' : '' }}>Menos popular</option>
                        <option value="pages_desc" {{ $filters['sort'] === 'pages_desc' ? 'selected' : '' }}>Más páginas</option>
                        <option value="year_desc" {{ $filters['sort'] === 'year_desc' ? 'selected' : '' }}>Más recientes</option>
                    </select>
                </div>

                {{-- CAMPO 4: Botones de acción --}}
                <div class="flex items-end gap-2">
                    {{-- Botón Buscar --}}
                    <button 
                        type="submit"
                        class="flex-1 rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors"
                    >
                        🔍 Buscar
                    </button>
                    
                    {{-- Botón Limpiar (solo visible si hay algún filtro activo) --}}
                    @if(!empty($filters['search']) || !empty($filters['genre']) || $filters['sort'] !== 'title_asc')
                        <a 
                            href="{{ route('books.index') }}"
                            class="rounded-md bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300 transition-colors"
                        >
                            ✕ Limpiar
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- Mostrar información de filtros activos --}}
        @if(!empty($filters['search']) || !empty($filters['genre']) || $filters['sort'] !== 'title_asc')
            <div class="mt-4 pt-4 border-t border-slate-200 text-sm text-slate-600">
                <strong>Filtros activos:</strong>
                @if(!empty($filters['search']))
                    📝 Búsqueda: "{{ $filters['search'] }}"
                @endif
                @if(!empty($filters['genre']))
                    📚 Género: {{ $filters['genre'] }}
                @endif
                @if($filters['sort'] !== 'title_asc')
                    ↕️ Orden:
                    @switch($filters['sort'])
                        @case('title_desc') Z → A @break
                        @case('popular_desc') Más popular @break
                        @case('popular_asc') Menos popular @break
                        @case('pages_desc') Más páginas @break
                        @case('year_desc') Más recientes @break
                    @endswitch
                @endif
            </div>
        @endif
    </div>

    {{-- Mostrar contador de resultados --}}
    {{-- Con paginación usamos ->total() para el total real, no solo la página actual --}}
    <div class="mb-6 text-sm text-slate-600">
        <strong>{{ $books->total() }}</strong> 
        @if($books->total() === 1)
            libro encontrado
        @else
            libros encontrados
        @endif
        <span class="text-slate-400">· Página {{ $books->currentPage() }} de {{ $books->lastPage() }}</span>
    </div>

    {{-- Grid de tarjetas --}}
    @if($books->isEmpty())
        {{-- Estado vacío --}}
        <div class="text-center py-20">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.242a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.242V21" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-slate-900">No hay libros</h3>
            <p class="mt-1 text-sm text-slate-500">Aún no hay libros en el catálogo.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($books as $book)

                {{-- Tarjeta --}}
                <div class="group flex flex-col bg-white rounded-xl shadow-sm ring-1 ring-slate-900/5 overflow-hidden hover:shadow-md transition-shadow">
                    {{-- Zona de portada --}}
                    {{-- Zona de portada: altura fija para evitar que imágenes externas rompan el grid --}}
                    <div class="h-56 w-full overflow-hidden bg-slate-100 flex-shrink-0">
                    @if($book->cover_url)
                        {{-- Si tiene portada, la mostramos --}}
                        <div class="h-full w-full">
                            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}"
                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300"
                                loading="lazy">
                        </div>
                    @else
                        {{-- Placeholder con gradiente y la inicial del título --}}
                        <div class="h-full w-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                            <span class="text-5xl font-bold text-white/80 select-none">
                                {{ strtoupper(substr($book->title, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                    </div>

                    {{-- Cuerpo de la tarjeta --}}
                    <div class="flex flex-col flex-1 p-4">

                         {{-- Título y autor --}}
                        <a href="{{ route('books.show', $book) }}" class="flex-1">
                            <h3 class="text-sm font-semibold text-slate-900 line-clamp-2 leading-snug group-hover:text-indigo-600 transition-colors">
                                {{ $book->title }}
                            </h3>
                            <p class="mt-1 text-xs text-slate-500">{{ $book->author }}</p>
                        </a>

                        {{-- Chip de género + páginas --}}
                        <div class="mt-3 flex items-center justify-between">
                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                {{ $book->genre ?? 'Sin género' }}
                            </span>
                            <span class="text-xs text-slate-400">{{ $book->pages }} págs.</span>
                        </div>

                        {{-- Acciones --}}
                        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between gap-x-2">

                            {{-- Añadir a librería / ya guardado --}}
                            @if($libraryIds->contains($book->id))
                                {{-- El libro ya está en la librería del usuario --}}
                                <div class="flex-1">
                                    <span class="w-full flex justify-center items-center gap-x-1 rounded-md bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        ✓ En tu librería
                                    </span>
                                </div>
                            @else
                                {{-- Todavía no está, mostramos el botón de añadir --}}
                                <form action="{{ route('library.store', $book) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-md bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-500 transition-colors">
                                        + Mi librería
                                    </button>
                                </form>
                            @endif

                            {{-- Acciones de admin --}}
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('books.edit', $book) }}"
                                    class="rounded-md px-2 py-1.5 text-xs font-medium text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition-colors">
                                    Editar
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST"
                                    onsubmit="return confirm('¿Eliminar este libro del catálogo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="rounded-md px-2 py-1.5 text-xs font-medium text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors">
                                        Eliminar
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Links de paginación --}}
        {{-- Solo se muestran si hay más de una página --}}
        @if($books->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $books->links() }}
            </div>
        @endif
    @endif

@endsection