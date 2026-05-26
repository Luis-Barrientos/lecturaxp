@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900">Buscar libros en OpenLibrary</h2>
        <p class="mt-2 text-sm text-slate-600">Busca un libro por título y agrega a nuestro catálogo</p>
    </div>

    {{-- Campo de búsqueda --}}
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6 mb-6">
        <div class="flex gap-2">
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Ej: Harry Potter, The Great Gatsby..."
                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
            <button 
                id="searchBtn"
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium"
            >
                🔍 Buscar
            </button>
        </div>
    </div>

    {{-- Resultados --}}
    <div id="results" class="space-y-4"></div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const resultsDiv = document.getElementById('results');

        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') performSearch();
        });

        async function performSearch() {
            const query = searchInput.value.trim();
            if (query.length < 2) {
                resultsDiv.innerHTML = '<p class="text-amber-600">Ingresa al menos 2 caracteres</p>';
                return;
            }

            searchBtn.disabled = true;
            searchBtn.textContent = '⏳ Buscando...';
            resultsDiv.innerHTML = '';

            try {
                const response = await fetch(`{{ route('books.search') }}?query=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (!data.results || data.results.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-slate-500">No se encontraron resultados</p>';
                    return;
                }

                resultsDiv.innerHTML = data.results.map(book => `
                    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-4 flex gap-4">
                        <img 
                            src="${book.cover_url || 'https://via.placeholder.com/80x120'}" 
                            alt="${book.title}"
                            class="w-20 h-32 object-cover rounded"
                        >
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900">${book.title}</h3>
                            <p class="text-sm text-slate-600">por ${book.author}</p>
                            ${book.published_year ? `<p class="text-xs text-slate-500">Año: ${book.published_year}</p>` : ''}
                            ${book.pages ? `<p class="text-xs text-slate-500">Páginas: ${book.pages}</p>` : ''}
                            ${book.genre ? `<p class="text-xs text-indigo-600 mt-1">📚 ${book.genre}</p>` : ''}
                        </div>
                        <form method="POST" action="{{ route('books.import-ol') }}" class="flex items-center">
                            @csrf
                            <input type="hidden" name="ol_id" value="${book.ol_id}">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors font-medium">
                                ✓ Agregar
                            </button>
                        </form>
                    </div>
                `).join('');
            } catch (error) {
                resultsDiv.innerHTML = '<p class="text-red-600">Error en la búsqueda. Intenta de nuevo.</p>';
                console.error(error);
            } finally {
                searchBtn.disabled = false;
                searchBtn.textContent = '🔍 Buscar';
            }
        }
    </script>
</div>
@endsection