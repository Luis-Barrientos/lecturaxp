<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class UserLibraryController extends Controller
{
    /**
     * Añade un libro del catálogo a la libreria personal del usuario 
     */
    public function store(Book $book) {
        //comprobamos si el usuario ya tiene este libro en su libreria
        // asi evitmaos duplicacion en la tabla pivot
        if (auth() -> user() -> books() -> where('book_id', $book -> id) -> exists()) {
            return back() -> with('error', 'Este libro ya está en tu librería.');
        }

        // attach () crea una fila en user_books con user_id, book_id y status
        auth() -> user() -> books() -> attach($book -> id, ['status' => 'leyendo']);

        return back() -> with('success', '¡Libro añadido a tu librería!');
    }

    /**
     * Quita un libro de la librería persona del usuario.
     * No borra el libro del catálogo, solo la relación en user_book.
     */
    public function destroy(Book $book) {
        auth() -> user() -> books() -> detach($book -> id);

        return back() -> with('success', 'Libro eliminado de tu librería.');
    }

    public function index()
{
    // Obtenemos los libros de la librería personal del usuario autenticado
    // withPivot nos da acceso al status guardado en book_user
    // Con load('readingLogs') obtenemos el historial de lectura para calcular progreso
    $books = auth() -> user() -> books() -> withPivot('status') -> with('readingLogs') -> get();
    $booksReading = $books -> where('pivot.status', 'leyendo') -> count();
    $booksCompleted = $books -> where('pivot.status', 'completado') -> count();
    $totalXP = $books -> sum(function($book) { 
        return $book -> readingLogs->sum('xp_earned');
    });

    return view('library.index', compact('books', 'booksReading', 'booksCompleted', 'totalXP'));
    }
}
