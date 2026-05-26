@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">
    {{-- ENCABEZADO CON BOTÓN ATRÁS --}}
    <div class="mb-6">
        <a href="{{ route('books.index') }}" class="text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
            ← Volver al catálogo
        </a>
    </div>

    {{-- CONTENEDOR PRINCIPAL --}}
    <div class="bg-white rounded-lg shadow-lg overflow-hidden ring-1 ring-slate-900/5">
        
        {{-- SECCIÓN 1: INFO DEL LIBRO (Portada + Detalles) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-8">
            
            {{-- PORTADA --}}
            <div class="md:col-span-1">
                @if($book->cover_url)
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" 
                        class="w-full rounded-lg shadow-md aspect-[2/3] object-cover">
                @else
                    <div class="w-full rounded-lg shadow-md aspect-[2/3] bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center">
                        <span class="text-6xl font-bold text-white/80 select-none">
                            {{ strtoupper(substr($book->title, 0, 1)) }}
                        </span>
                    </div>
                @endif

                {{-- BOTÓN AÑADIR A LIBRERÍA --}}
                <div class="mt-6">
                    @if($inLibrary)
                        <span class="w-full block text-center rounded-md bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                            ✓ En tu librería
                        </span>
                    @else
                        <form action="{{ route('library.store', $book) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full rounded-md bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors">
                                + Añadir a mi librería
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- INFORMACIÓN DEL LIBRO --}}
            <div class="md:col-span-2 space-y-6">
                
                {{-- TÍTULO Y AUTOR --}}
                <div>
                    <h1 class="text-4xl font-bold text-slate-900 mb-2">{{ $book->title }}</h1>
                    <p class="text-lg text-slate-600">por <strong>{{ $book->author }}</strong></p>
                </div>

                {{-- CALIFICACIÓN PROMEDIO --}}
                <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                    <div class="flex items-center gap-2 mb-2">
                        {{-- ESTRELLAS --}}
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating ?? 0))
                                    <span class="text-2xl text-yellow-400">★</span>
                                @elseif($i - 0.5 <= ($averageRating ?? 0))
                                    <span class="text-2xl text-yellow-400" style="width: 50%; overflow: hidden;">★</span>
                                @else
                                    <span class="text-2xl text-slate-300">★</span>
                                @endif
                            @endfor
                        </div>
                        {{-- NÚMERO Y PROMEDIO --}}
                        <div>
                            <span class="text-lg font-semibold text-slate-900">
                                {{ number_format($averageRating ?? 0, 1) }}/5
                            </span>
                            <span class="text-sm text-slate-600">
                                ({{ $reviews->count() }} {{ $reviews->count() === 1 ? 'reseña' : 'reseñas' }})
                            </span>
                        </div>
                    </div>
                </div>

                {{-- DETALLES DEL LIBRO --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-sm font-medium text-slate-500">Género</span>
                        <span class="text-slate-900">{{ $book->genre ?? 'Sin género' }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-slate-500">Páginas</span>
                        <span class="text-slate-900">{{ $book->pages ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-slate-500">Año de publicación</span>
                        <span class="text-slate-900">{{ $book->published_year ?? 'Desconocido' }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-slate-500">ISBN</span>
                        <span class="text-slate-900 font-mono text-sm">{{ $book->isbn ?? 'N/A' }}</span>
                    </div>
                </div>

                {{-- DESCRIPCIÓN --}}
                @if($book->description)
                    <div>
                        <h3 class="font-semibold text-slate-900 mb-2">Sinopsis</h3>
                        <p class="text-slate-600 leading-relaxed">{{ $book->description }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- SECCIÓN 2: FORMULARIO DE RESEÑA (Solo usuarios loggeados) --}}
        @if(auth()->check())
        <div class="border-t border-slate-200 px-8 py-6 bg-slate-50">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">
                {{ $userReview ? '✏️ Edita tu reseña' : '⭐ Deja tu reseña' }}
            </h2>

            <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                @csrf
                
                {{-- Campo oculto: ID del libro --}}
                <input type="hidden" name="book_id" value="{{ $book->id }}">

                {{-- CALIFICACIÓN (estrellas interactivas) --}}
                <div>
                    <label class="block text-sm font-medium text-slate-900 mb-3">
                        Mi calificación
                    </label>
                    <div class="flex gap-2" id="ratingStars">
                        @for($i = 1; $i <= 5; $i++)
                            <button 
                                type="button" 
                                class="rating-star text-4xl transition-colors"
                                data-rating="{{ $i }}"
                                data-selected="{{ $userReview && $userReview->rating >= $i ? 'true' : 'false' }}"
                            >
                                ★
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" id="ratingInput" name="rating" value="{{ $userReview?->rating ?? 0 }}" required>
                </div>

                {{-- COMENTARIO --}}
                <div>
                    <label for="comment" class="block text-sm font-medium text-slate-900 mb-2">
                        Comentario (opcional)
                    </label>
                    <textarea 
                        id="comment" 
                        name="comment" 
                        rows="4"
                        maxlength="500"
                        placeholder="¿Qué te pareció este libro?"
                        class="w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"
                    >{{ $userReview?->comment }}</textarea>
                    <span class="text-xs text-slate-500">Máximo 500 caracteres</span>
                </div>

                {{-- BOTONES --}}
                <div class="flex gap-2 pt-2">
                    <button 
                        type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-500 transition-colors"
                    >
                        {{ $userReview ? '✏️ Actualizar reseña' : '⭐ Publicar reseña' }}
                    </button>

                    @if($userReview)
                        <form action="{{ route('reviews.destroy', $userReview) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                onclick="return confirm('¿Eliminar tu reseña?')"
                                class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500 transition-colors"
                            >
                                🗑️ Eliminar
                            </button>
                        </form>
                    @endif
                </div>
            </form>
        </div>
        @else
        {{-- MENSAJE PARA NO LOGGEADOS --}}
        <div class="border-t border-slate-200 px-8 py-6 bg-slate-50">
            <p class="text-slate-600">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-semibold">Inicia sesión</a> 
                para dejar una reseña
            </p>
        </div>
        @endif

        {{-- SECCIÓN 3: LISTA DE RESEÑAS --}}
        <div class="border-t border-slate-200 px-8 py-6">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">
                Reseñas ({{ $reviews->count() }})
            </h2>

            @if($reviews->isEmpty())
                <p class="text-slate-500 text-center py-8">
                    Aún no hay reseñas. ¡Sé el primero en comentar!
                </p>
            @else
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="border border-slate-200 rounded-lg p-4 bg-slate-50">
                            
                            {{-- ENCABEZADO: Autor + Fecha --}}
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $review->user->name }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $review->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                
                                {{-- ESTRELLAS --}}
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-slate-300' }}">★</span>
                                    @endfor
                                </div>
                            </div>

                            {{-- COMENTARIO --}}
                            @if($review->comment)
                                <p class="text-slate-700 leading-relaxed">{{ $review->comment }}</p>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- JAVASCRIPT PARA ESTRELLAS INTERACTIVAS --}}
<script>
document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
        const rating = this.dataset.rating;
        document.getElementById('ratingInput').value = rating;
        
        // Actualizar visual de estrellas
        document.querySelectorAll('.rating-star').forEach(s => {
            if (s.dataset.rating <= rating) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-slate-300');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-slate-300');
            }
        });
    });

    // Hover effect
    star.addEventListener('mouseover', function() {
        const rating = this.dataset.rating;
        document.querySelectorAll('.rating-star').forEach(s => {
            if (s.dataset.rating <= rating) {
                s.classList.add('text-yellow-400');
                s.classList.remove('text-slate-300');
            } else {
                s.classList.remove('text-yellow-400');
                s.classList.add('text-slate-300');
            }
        });
    });
});

// Reset al quitar hover
document.getElementById('ratingStars').addEventListener('mouseleave', function() {
    const rating = document.getElementById('ratingInput').value;
    document.querySelectorAll('.rating-star').forEach(s => {
        if (s.dataset.rating <= rating) {
            s.classList.add('text-yellow-400');
            s.classList.remove('text-slate-300');
        } else {
            s.classList.remove('text-yellow-400');
            s.classList.add('text-slate-300');
        }
    });
});

// Inicializar estrellas según rating actual
window.addEventListener('DOMContentLoaded', function() {
    const rating = document.getElementById('ratingInput').value;
    document.querySelectorAll('.rating-star').forEach(s => {
        if (s.dataset.rating <= rating) {
            s.classList.add('text-yellow-400');
            s.classList.remove('text-slate-300');
        } else {
            s.classList.remove('text-yellow-400');
            s.classList.add('text-slate-300');
        }
    });
});
</script>

@endsection