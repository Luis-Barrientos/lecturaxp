<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Achievement;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Panel principal del admin — muestra un resumen general de la aplicación.
     * Esta es la primera página que ve el admin al entrar en /admin
     */
    public function index()
    {
        // Contamos los totales para mostrar en las tarjetas del panel
        $totalUsers = User::count();
        $totalBooks = Book::count();
        $totalAdmins = User::where('role', 'admin')->count();

        return view('admin.index', compact('totalUsers', 'totalBooks', 'totalAdmins'));
    }

    /**
     * Lista todos los usuarios registrados en la aplicación.
     * El admin puede ver y cambiar el rol de cada usuario desde aquí.
     */
    public function users()
    {
        // orderBy('created_at', 'desc') → los más nuevos primero
        $users = User::orderBy('created_at', 'desc')->get();

        return view('admin.users', compact('users'));
    }

    /**
     * Cambia el rol de un usuario concreto.
     * Se llama desde el formulario de la lista de usuarios con PATCH.
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            // Solo permite los valores definidos en el enum de la DB
            'role' => 'required|in:lector,admin',
        ]);

        // Protección: no permitimos que el admin se quite su propio rol
        // Así evitamos que la aplicación se quede sin ningún admin
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'No puedes quitarte el rol de administrador a ti mismo.');
        }

        $user->update(['role' => $validated['role']]);

        return back()->with('success', "Rol de {$user->name} actualizado correctamente.");
    }

    /**
     * Lista todos los libros de todos los usuarios.
     * A diferencia de BookController, aquí el admin ve TODOS los libros.
     */
    public function books() {

        //with('user') carga el usuario dueño de cada libro sin N+1
        $books = Book::orderBy('created_at', 'desc')->get();

        return view('admin.books', compact('books'));
    }

    /**
     * Elimina cualquier libro, independientemente de quién sea el dueño. 
     * Solo accesible para admins.
     */
    public function destroyBook(Book $book) {

        $book -> delete();

        return back() -> with('success', 'Libro eliminado correctamente.');
    }

    /**
     * Lista todos los logros existentes en la plataforma.
     */
    public function achievements() {
        
        $achievements = Achievement::orderBy('condition_type')->get();
        return view('admin.achievements.index', compact('achievements'));
    }

    /**
     * Muestra el formulario para crear un nuevo logro.
     */
    public function createAchievement() {
        
        return view('admin.achievements.create');
    }

    /**
     * Guarda un nuevo logro en la base de datos,
     */
    public function storeAchievement(Request $request) {

        $validated = $request -> validate([
            'name'            => 'required|string|max:255',
            'slug'            => 'required|string|unique:achievements,slug',
            'description'     => 'required|string|max:500',
            'icon'            => 'required|string|max:10',
            'condition_type'  => 'required|in:total_logs,streak_days,total_xp,completed_books',
            'condition_value' => 'required|integer|min:1',
            'xp_reward'       => 'required|integer|min:0',
        ]);

        Achievement::create($validated);

        return redirect() -> route('admin.achievements')
            -> with('success', 'Logro creado correctamente.');
    }

    /**
     * Muestra el formulario para editar un logro existente.
     */
    public function editAchievement(Achievement $achievement) {
    return view('admin.achievements.edit', compact('achievement'));
    }

    /**
     * Actualiza un logro en la base de datos.
     */
    public function updateAchievement(Request $request, Achievement $achievement) {
        $validated = $request -> validate([
        'name'            => 'required|string|max:255',
        // unique pero ignorando el slug del logro actual
        'slug'            => 'required|string|unique:achievements,slug,' . $achievement->id,
        'description'     => 'required|string|max:500',
        'icon'            => 'required|string|max:10',
        'condition_type'  => 'required|in:total_logs,streak_days,total_xp,completed_books',
        'condition_value' => 'required|integer|min:1',
        'xp_reward'       => 'required|integer|min:0',
    ]);

        $achievement -> update($validated);

        return redirect()->route('admin.achievements')
            -> with('success', 'Logros actualizados correctamente.');
    }

    /**
     * Elimina un logro de la base de datos.
     * Tambiéb se elimian automáticamente los registros de user_achievements
     * gracias al onDelete('cascade') que pusimos en la migración.
     */
    public function destroyAchievement(Achievement $achievement) {
        $achievement -> delete();

        return back() -> with('success', 'Logro eliminado correctamente.');
    }

    /**
     * Panel de importación de libros desde OpenLibrary.
     */
    public function importBooksForm() {

        $totalBooks = Book::count();
        return view('admin.import-books', compact('totalBooks'));
    }

    /**
     * Buscar libros en OpenLibray (AJAX)
     */
    /**
     * Busca libros en OpenLibrary (AJAX)
     */
    public function searchBooksOpenLibrary(Request $request)
    {
        $query = $request->input('query', '');
        
        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'message' => 'Ingresa al menos 2 caracteres']);
        }

        $service = new \App\Services\OpenLibraryService();
        $results = $service->search($query, 20);

        return response()->json(['results' => $results]);
    }

    /**
     * Importa un libro desde OpenLibrary a la BD
     */
    public function importBookFromOpenLibrary(Request $request)
    {
        $validated = $request->validate([
            'ol_id' => 'required|string',
        ]);

        $service = new \App\Services\OpenLibraryService();
        $bookData = $service->getBook($validated['ol_id']);

        if (!$bookData) {
            return response()->json(['success' => false, 'message' => 'No se pudo obtener el libro'], 400);
        }

        // Verificar si ya existe por ISBN
        if ($bookData['isbn']) {
            $exists = Book::where('isbn', $bookData['isbn'])->first();
            if ($exists) {
                return response()->json(['success' => false, 'message' => 'Este libro ya existe'], 400);
            }
        }

        // Crear el libro
        $book = Book::create([
            'title' => $bookData['title'],
            'author' => $bookData['author'],
            'isbn' => $bookData['isbn'] ?? 'ISBN-' . uniqid(),
            'pages' => $bookData['pages'] ?? 0,
            'genre' => $bookData['genre'],
            'cover_url' => $bookData['cover_url'],
            'description' => $bookData['description'],
            'published_year' => $bookData['published_year'],
        ]);

        return response()->json([
            'success' => true, 
            'message' => "'{$book->title}' importado correctamente",
            'book' => $book
        ]);
    }

    /**
     * Importa un lote de libros desde OpenLibrary basado en géneros
     */
    public function bulkImportBooks(Request $request) {

        // Validamos que la cantidad esté entre 10 y 500 libros
        $validated = $request -> validate([
            'quantity' => 'required|integer|min:10|max:500',
            'genres' => 'required|string|min:2',
        ]);

        // Importamos el servicio OpenLibrary que ya creamos antes
        $openLibraryService = new \App\Services\OpenLibraryService();

        $imported = 0;      // Contador de libros importados exitosos
        $skipped = 0;       // Contador de libros omitidos (duplicados)
        $errors= 0;        // Contador de errores
        $booksPerGenre = intval($validated['quantity'] / max(1,substr_count($validated['genres'], ',') + 1));

        // Dividimos los géneros por coma y limpiamos espacios
        $genres = array_map('trim', explode(',', $validated['genres']));

        // Recorremos cada género y buscamos libros
        foreach ($genres as $genre) {
    try {
        // 1. Tabla de traducciones (AQUÍ, antes de buscar)
        $genreTranslations = [
            'Ficción literaria' => 'literary fiction',
            'Ciencia ficción'   => 'science fiction',
            'Fantasía'          => 'fantasy',
            'Misterio'          => 'mystery',
            'Thriller / Suspense' => 'thriller',
            'Terror'            => 'horror',
            'Romance'           => 'romance',
            'Aventura'          => 'adventure',
            'Biografía'         => 'biography',
            'Historia'          => 'history',
            'Autoayuda'         => 'self help',
            'Poesía'            => 'poetry',
        ];
        $searchQuery = $genreTranslations[$genre] ?? $genre;

        // 2. Buscar con término en inglés
        $books = $openLibraryService->search($searchQuery, $booksPerGenre);

        if (empty($books)) {
            continue;
        }

        // 3. Recorrer libros encontrados
        foreach ($books as $bookData) {
            try {
                // Verificar duplicado por ISBN
                if ($bookData['isbn'] && Book::where('isbn', $bookData['isbn'])->exists()) {
                    $skipped++;
                    continue;
                }

                // Verificar duplicado por título+autor si no tiene ISBN
                if (!$bookData['isbn'] && Book::where('title', $bookData['title'])
                    ->where('author', $bookData['author'])->exists()) {
                    $skipped++;
                    continue;
                }

                // Crear el libro
                Book::create([
                    'title'          => $bookData['title'],
                    'author'         => $bookData['author'],
                    'isbn'           => $bookData['isbn'] ?? 'OL-' . uniqid(),
                    'pages'          => $bookData['pages'] ?? 0,
                    'genre'          => $genre,
                    'cover_url'      => $bookData['cover_url'] ?? null,
                    'description'    => $bookData['description'] ?? null,
                    'published_year' => $bookData['published_year'] ?? null,
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors++;
            }
        }
    } catch (\Exception $e) {
        continue;
    }
}

        // Retornamos un JSON con los resultados para actualizar el panel
        return response() -> json([
            'success' => true,
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $errors,
            'total_books' => $imported + $skipped + $errors,
            'message' => "✓ Importación completada: {$imported} libros agregados, {$skipped} omitidos, {$errors} con errores"
        ]);
    }

}    