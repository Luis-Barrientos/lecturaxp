<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Guarda una nueva reseña o actualiaza la existente.
     * POST /reviews
     */
    public function store(Request $request) {

        // Validar que el usuario envíe rating y opcionalmente comment
        $validated = $request -> validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // si el usuario ya reseñó este libro, actualizamos en lugar de crear
        $review = Review::where('user_id', auth() -> id())
                        -> where('book_id', $validated['book_id'])
                        -> first();

        if ($review) {
            // Actualizamos reseñas existentes
            $review -> update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
            $message = '¡Reseña actualizada!';
        }else {
            // Creamos nueva reseña
            $review = Review::create([
                'user_id' => auth() -> id(),
                'book_id' => $validated['book_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
            $message = '¡Reseña creada!';
        }

        return back() -> with('success', $message);
    }

    /**
     * Actualizar una reseña existente
     * PATCH /reviews/{review}
     */
    public function update(Request $request, Review $review) {

        // Verificar que el usuario es deuño de la reseña
        if ($review -> user_id !== auth() -> id()) {
            return back() -> with('error', 'No puedes editar reseñas de otros usuarios.');
        }

        // Validar
        $validated = $request -> validate([
            'rating' => 'required|integer|min:1|max:5', 
            'comment' => 'nullable|string|max:500',
        ]);

        $review -> update($validated);

        return back() -> with('success', '¡Reseña actualizada!');
    }

    /**
     * Eliminar una reseña
     * DELETE /reviews/{review}
     */
    public function destroy(Review $review) {

    
        // Verificar que el usuario es dueño de la reseña
        if ($review -> user_id !== auth() -> id()) {
            return back() -> with('error', 'No puedes eliminar reseñas de otros usuarios.');
        }

        $review -> delete();

        return back() -> with('success', '¡Reseña eliminada!');
    }
}