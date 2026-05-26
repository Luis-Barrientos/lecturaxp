@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="md:flex md:items-center md:justify-between mb-8">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-slate-900 sm:text-3xl sm:truncate">
                Nuevo Libro
            </h2>
            <p class="mt-1 text-sm text-slate-500">Introduce los detalles del libro para tu colección.</p>
        </div>
    </div>

    <!-- Formulario con diseño moderno -->
    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 sm:rounded-xl md:col-span-2">
        <form action="{{ route('books.store') }}" method="POST" class="px-4 py-6 sm:p-8">
            @csrf
            
            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <!-- Título -->
                <div class="sm:col-span-4">
                    <label for="title" class="block text-sm font-medium leading-6 text-slate-900">Título del libro</label>
                    <div class="mt-2">
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Autor -->
                <div class="sm:col-span-4">
                    <label for="author" class="block text-sm font-medium leading-6 text-slate-900">Autor/a</label>
                    <div class="mt-2">
                        <input type="text" name="author" id="author" value="{{ old('author') }}" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('author') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- ISBN -->
                <div class="sm:col-span-3">
                    <label for="isbn" class="block text-sm font-medium leading-6 text-slate-900">ISBN</label>
                    <div class="mt-2">
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn') }}" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('isbn') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Género (combobox con autocomplete + chip — Alpine.js) -->
                {{-- x-data define el estado local de este componente, solo vive aquí --}}
                <div class="sm:col-span-3"
                    x-data="{
                        genres: @js(config('genres.list')),
                        search: '',
                        selected: @js(old('genre', '')),
                        open: false,
                        get filtered() {
                            if (!this.search) return this.genres;
                            return this.genres.filter(g => g.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        select(genre) {
                            this.selected = genre;
                            this.search = '';
                            this.open = false;
                        },
                        clear() {
                            this.selected = '';
                            this.search = '';
                            this.$nextTick(() => this.$refs.genreInput.focus());
                        }
                    }"
                    @click.outside="open = false"
                >
                    <label class="block text-sm font-medium leading-6 text-slate-900">Género</label>

                    {{-- Input hidden: este es el que realmente se envía al servidor con el valor seleccionado --}}
                    <input type="hidden" name="genre" :value="selected">

                    <div class="mt-2 relative">

                        {{-- Chip: píldora que aparece cuando hay un género seleccionado --}}
                        <div x-show="selected" x-transition class="mb-2">
                            <span class="inline-flex items-center gap-x-1.5 rounded-full bg-indigo-100 px-3 py-1 text-sm font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/20">
                                <span x-text="selected"></span>
                                {{-- Botón × para borrar la selección y volver al input --}}
                                <button type="button" @click="clear()" class="rounded-full hover:bg-indigo-200 p-0.5 transition-colors" aria-label="Eliminar género">
                                    <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z"/>
                                    </svg>
                                </button>
                            </span>
                        </div>

                        {{-- Input de búsqueda: visible solo cuando NO hay selección --}}
                        <div x-show="!selected">
                            <input
                                type="text"
                                x-ref="genreInput"
                                x-model="search"
                                @input="open = search.length > 0"
                                placeholder="Buscar género..."
                                autocomplete="off"
                                class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            >
                        </div>

                        {{-- Dropdown de resultados --}}
                        <div
                            x-show="open && !selected"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute z-10 mt-1 max-h-48 w-full overflow-auto rounded-md bg-white py-1 shadow-lg ring-1 ring-slate-200 text-sm"
                        >
                            {{-- @mousedown.prevent evita que el input pierda foco antes de registrar el click --}}
                            <template x-for="genre in filtered" :key="genre">
                                <div
                                    @mousedown.prevent="select(genre)"
                                    class="cursor-pointer px-4 py-2 text-slate-900 hover:bg-indigo-50 hover:text-indigo-700 transition-colors"
                                    x-text="genre"
                                ></div>
                            </template>
                            <p x-show="filtered.length === 0" class="px-4 py-2 text-slate-400 italic">Sin resultados</p>
                        </div>

                    </div>
                    @error('genre') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <!-- Páginas -->
                <div class="sm:col-span-2">
                    <label for="pages" class="block text-sm font-medium leading-6 text-slate-900">Nº de Páginas</label>
                    <div class="mt-2">
                        <input type="number" name="pages" id="pages" value="{{ old('pages') }}" required
                            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        @error('pages') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <!-- Año de publicación -->
<div class="sm:col-span-2">
    <label for="published_year" class="block text-sm font-medium leading-6 text-slate-900">Año de publicación</label>
    <div class="mt-2">
        <input type="number" name="published_year" id="published_year" value="{{ old('published_year') }}"
            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        @error('published_year') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<!-- URL de portada -->
<div class="sm:col-span-6">
    <label for="cover_url" class="block text-sm font-medium leading-6 text-slate-900">URL de portada</label>
    <div class="mt-2">
        <input type="url" name="cover_url" id="cover_url" value="{{ old('cover_url') }}"
            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
        @error('cover_url') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<!-- Descripción -->
<div class="sm:col-span-6">
    <label for="description" class="block text-sm font-medium leading-6 text-slate-900">Descripción</label>
    <div class="mt-2">
        <textarea name="description" id="description" rows="4"
            class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">{{ old('description') }}</textarea>
        @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
            </div>
            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-slate-900/10 pt-6">
                <a href="{{ route('books.index') }}" class="text-sm font-semibold leading-6 text-slate-900">Cancelar</a>
                <button type="submit" 
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                    Guardar libro
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
