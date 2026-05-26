@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900">📚 Importar libros desde OpenLibrary</h2>
        <p class="mt-1 text-sm text-slate-600">Busca y agrega libros a tu catálogo. Actual: {{ $totalBooks }} libros</p>
    </div>

    {{-- AQUÍ VA EL PANEL DE IMPORTACIÓN EN LOTE --}}
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6 mb-6">
        <h3 class="text-lg font-bold mb-4"> Importación en Lote desde OpenLibrary</h3>
        <p class="text-sm text-slate-600 mb-4">Importa múltiples libros a la vez especificando géneros</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Cantidad de libros (10-500)</label>
                <input 
                    type="number" 
                    id="quantityInput" 
                    min="10" 
                    max="500" 
                    value="50"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="50"
                >
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Géneros (separados por coma)</label>
                <input 
                    type="text" 
                    id="genresInput" 
                    list="genresList"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Ficción literaria, Misterio, Romance"
                >
                <x-genres-datalist />
            </div>
        </div>

        <button 
            id="btnImport"
            class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium"
        >
             Iniciar Importación
        </button>
    </div>

    {{-- Campo de búsqueda --}}
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-6 mb-6">
        <div class="flex gap-2">
            <input 
                type="text" 
                id="searchInput" 
                placeholder="Busca por título o autor (ej: 1984, George Orwell)..."
                class="flex-1 px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
            <button 
                id="searchBtn"
                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium"
            >
                 Buscar
            </button>
        </div>
    </div>

    {{-- Resultados --}}
    <div id="results" class="space-y-3"></div>

    {{-- Toast de notificaciones --}}
    <div id="toast" class="fixed bottom-4 right-4 px-4 py-3 bg-white rounded-lg shadow-lg hidden"></div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const resultsDiv = document.getElementById('results');
        const toast = document.getElementById('toast');

        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') performSearch();
        });
        
        // Event listener para el botón de importación en lote
        document.getElementById('btnImport').addEventListener('click', async () => {
            const quantity = document.getElementById('quantityInput').value;
            const genres = document.getElementById('genresInput').value;

            if (!quantity || quantity < 10 || quantity > 500) {
                showToast('Cantidad debe estar entre 10 y 500', 'warning');
                return;
            }

            if (!genres.trim()) {
                showToast('Ingresa al menos un género', 'warning');
                return;
            }

            const btnImport = document.getElementById('btnImport');
            btnImport.disabled = true;
            btnImport.textContent = '⏳ Importando...';

            try {
                const response = await fetch(`{{ route('admin.books.bulk-import') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        quantity: parseInt(quantity),
                        genres: genres
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showToast(`✓ ${data.imported} libros importados, ${data.skipped} saltados`, 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showToast('✗ Error en la importación', 'error');
                }
            } catch (error) {
                showToast('Error al importar', 'error');
                console.error(error);
            } finally {
                btnImport.disabled = false;
                btnImport.textContent = '🚀 Iniciar Importación';
            }
        });

        async function performSearch() {
            const query = searchInput.value.trim();
            if (query.length < 2) {
                showToast('Ingresa al menos 2 caracteres', 'warning');
                return;
            }

            searchBtn.disabled = true;
            searchBtn.textContent = '⏳ Buscando...';
            resultsDiv.innerHTML = '';

            try {
                const response = await fetch(`{{ route('admin.books.search-ol') }}?query=${encodeURIComponent(query)}`);
                const data = await response.json();

                if (!data.results || data.results.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-slate-500 text-center py-8">No se encontraron resultados</p>';
                    return;
                }

                resultsDiv.innerHTML = data.results.map(book => `
                    <div class="bg-white rounded-lg shadow-sm ring-1 ring-slate-900/5 p-4 flex gap-4 hover:shadow-md transition-shadow">
                        <div class="flex-shrink-0">
                            <img 
                                src="${book.cover_url || 'https://via.placeholder.com/80x120?text=Sin+portada'}" 
                                alt="${book.title}"
                                class="w-20 h-32 object-cover rounded-md"
                                onerror="this.src='https://via.placeholder.com/80x120?text=Error'"
                            >
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900">${escapeHtml(book.title)}</h3>
                            <p class="text-sm text-slate-600">por ${escapeHtml(book.author)}</p>
                            <div class="flex gap-4 mt-2 text-xs text-slate-500">
                                ${book.published_year ? `<span>📅 ${book.published_year}</span>` : ''}
                                ${book.pages ? `<span>📄 ${book.pages} págs</span>` : ''}
                                ${book.genre ? `<span>📚 ${escapeHtml(book.genre)}</span>` : ''}
                            </div>
                        </div>
                        <div class="flex-shrink-0 flex items-center">
                            <button 
                                onclick="importBook('${book.ol_id}')"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors font-medium whitespace-nowrap"
                            >
                                ✓ Agregar
                            </button>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                showToast('Error en la búsqueda', 'error');
                console.error(error);
            } finally {
                searchBtn.disabled = false;
                searchBtn.textContent = '🔍 Buscar';
            }
        }

        async function importBook(olId) {
            try {
                const response = await fetch(`{{ route('admin.books.import-ol') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ ol_id: olId })
                });

                const data = await response.json();

                if (data.success) {
                    showToast('✓ ' + data.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('✗ ' + data.message, 'error');
                }
            } catch (error) {
                showToast('Error al importar', 'error');
                console.error(error);
            }
        }

        function showToast(message, type = 'info') {
            toast.textContent = message;
            toast.className = 'fixed bottom-4 right-4 px-4 py-3 bg-white rounded-lg shadow-lg';
            
            const colors = {
                'success': 'bg-green-100 text-green-800',
                'error': 'bg-red-100 text-red-800',
                'warning': 'bg-amber-100 text-amber-800',
                'info': 'bg-indigo-100 text-indigo-800'
            };
            
            toast.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg ${colors[type] || colors['info']}`;
            toast.classList.remove('hidden');
            
            setTimeout(() => toast.classList.add('hidden'), 3000);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</div>
@endsection