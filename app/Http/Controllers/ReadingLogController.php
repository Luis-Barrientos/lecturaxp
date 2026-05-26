<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ReadingLog;
use App\Services\XPCalculator;
use Illuminate\Http\Request;

class ReadingLogController extends Controller
{
    public function index(Book $book) {
        $logs = $book->readingLogs()
                    ->where('user_id', auth()->id())
                    ->orderBy('date', 'desc')
                    ->get();

        return view('reading-logs.index', compact('book', 'logs'));
    }

    public function create(Book $book) {
        // Calculamos cuántas páginas quedan por leer
        $pagesAlreadyRead = $book -> readingLogs()
            ->where('user_id', auth()->id())
            ->sum('pages_read');

        $pagesRemaining = max(0, $book->pages - $pagesAlreadyRead);

        //Si el libro está completo, no tiene sentido registrar más sesiones
        if ($pagesRemaining === 0) {
            return redirect()
                -> route ('books.reading-logs.index', $book)
                -> with ('info', 'Este libro ya esta completo. No puedes añadir más sesiones.');
        }

        return view('reading-logs.create', compact('book', 'pagesRemaining'));
    }

    public function store(Request $request, Book $book) {

        $pagesAlreadyRead = $book->readingLogs()
            ->where('user_id', auth()->id())
            ->sum('pages_read');

        $pagesRemaining = max(0, $book->pages - $pagesAlreadyRead);

        $validated = $request->validate([
            'date'         => 'required|date|before_or_equal:today',
            'pages_read'   => 'required|integer|min:1|max:' . $pagesRemaining,
            'comments'     => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['book_id'] = $book->id;
        $validated['is_completed'] = false; // ← valor por defecto

        // Luego la auto-detección sobreescribe si aplica
        if ($pagesAlreadyRead + $validated['pages_read'] >= $book->pages) {
            $validated['is_completed'] = true;
        }

        // Y al final, ya sabemos el valor real
        $mensaje = $validated['is_completed']
            ? '🎉 ¡Felicidades! Has terminado el libro. +50 XP bonus.'
            : '¡Sesión de lectura registrada!';

        ReadingLog::create($validated);

        return redirect()
            ->route('books.reading-logs.index', $book)
            ->with('success', $mensaje);
    }

    public function edit(Book $book, ReadingLog $readingLog) {
        //Verificamos que el log pertenece al usuario actual
        if($readingLog -> user_id !== auth() -> id()) {
            abort(403);
        }

        return view('reading-logs.edit', compact('book', 'readingLog'));
    }

    public function update(Request $request, Book $book, ReadingLog $readingLog) {
    // Verificamos que el log pertenece al usuario actual
    if ($readingLog->user_id !== auth()->id()) {
        abort(403);
        }

        // Calculamospáginas disponibles SIN contar las de este log
        $pagesAlreadyRead = $book -> readingLogs()
            ->where ('user_id', auth() -> id())
            ->where ('id', '!=', $readingLog -> id)
            ->sum ('pages_read');

            $pagesRemaining = max(0, $book -> pages - $pagesAlreadyRead);

            $validated = $request -> validate ([
        'pages_read' => 'required|integer|min:1|max:' . $pagesRemaining,
        'comments'   => 'nullable|string|max:1000',
    ]);

    $readingLog->update($validated);

    return redirect()
        ->route('books.reading-logs.index', $book)
        ->with('success', 'Sesión actualizada correctamente.');
    }
    public function destroy(Book $book, ReadingLog $readingLog) {
        if ($readingLog->user_id !== auth()->id()) {
            abort(403);
        }

        // Obtener el usuario y el XP ganado en esta sesión
        $user = $readingLog->user;
        $xpEarned = $readingLog->xp_earned ?? 0;

        // Restar el XP del total
        $user->total_xp = max(0, $user->total_xp - $xpEarned);

        // Recalcular el nivel basado en el nuevo XP total
        $xpCalculator = new XPCalculator();
        $user->current_level = $xpCalculator->calculateLevel($user->total_xp, $user->current_level);

        // Guardar los cambios
        $user->save();

        // Eliminar la sesión de lectura
        $readingLog->delete();

        return redirect()
            ->route('books.reading-logs.index', $book)
            ->with('success', 'Sesión eliminada.');
    }
}