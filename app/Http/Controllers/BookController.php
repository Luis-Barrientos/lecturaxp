<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Muestra la lista de todos los libros con busqueda, filtro y ordenación.
     */

    public function index(Request $request) {
        // Leer parámetros de la URL
        $search = $request -> query('search', '');    // Búsqueda de texto
        $genre = $request -> query('genre', '');      // Filtro de género
        $sort = $request -> query('sort', 'title_asc');// Tipo de ordenación

        // Construir la consulta 
        // withCount('users') agrega una columna virtual 'users_count' con el número de usuarios
        // que tienen este libro en su librería. Esto es lo que usamos como "popularidad"
        $query = Book::withCount('users');

        // Aplicar filtro de Búsqueda
        // El método when() es muy útil: solo aplica la clausula si la condición es verdadera
        // Si $search no está vacío, busca en título O autor (de ambos lados con %)
        $query -> when(!empty($search), function ($q) use ($search) {
            return $q -> where('title', 'LIKE', "%{$search}%")
                      -> orWhere('author', 'LIKE', "%{$search}%");
        });

        // Aplicar filtro de Género
        // Si el usuario selecciona un género, filtramos solo esos libros
        $query -> when(!empty($genre), function ($q) use ($genre) {
            return $q -> where('genre', $genre);
        });

        // Aplicar Ordenación según el tipo seleccionado
        // Soportamos 6 tipos de ordenación diferentes.  
        match ($sort) {
            'title_asc' => $query -> orderBy('title', 'asc'),   // A -> Z
            'title_desc' => $query -> orderBy('title', 'desc'), // Z -> A
            'popular_desc' => $query -> orderBy('users_count', 'desc'), // Más popular primero
            'popular_asc' => $query -> orderBy('users_count', 'asc'),   // Menos popular primero
            'pages_desc' => $query -> orderBy('pages', 'desc'),     // Más páginas primero
            'year_desc' => $query -> orderBy('published_year', 'desc'), // Más reciente primero
            default => $query -> orderBy('title', 'asc'),
        };

        // Ejecutar la consulta con paginación (24 libros por página)
        // ->paginate() en lugar de ->get() evita cargar todos los libros de golpe,
        // lo que causaría páginas de miles de píxeles de altura con muchos libros.
        $books = $query -> paginate(24) -> withQueryString();

        // Obtener lista de géneros para el dropdown
        // distinct() evita duplicados
        // whereNotNull('genre') excluye libros sin genero asignado
        // pluck('genre') extrae solo columna 'genre' como array
        $genres = Book::distinct() 
                        -> whereNotNull('genre')
                        -> orderBy('genre')
                        -> pluck('genre');

        // Ids de los libros en la librería del usuario
        // Igual que antes - esto marca cuáles libros el usuario ya tiene 
        $libraryIds = auth() -> user() -> books() -> pluck('books.id');

        // Pasar datos a la vista
        // Pasamos:
        // - books: libros ya filtrados y ordenados
        // - genres: lista de géneros para el dropdown
        // - libraryIds: IDs de libros que el usuario ya tiene
        // - filters: los valores actuales de búsqueda/género/ordenación
        return view('books.index', [
            'books' => $books,
            'libraryIds' => $libraryIds,
            'genres' => $genres,
            'filters' => [
                'search' => $search,
                'genre' => $genre,
                'sort' => $sort,
            ],
        ]);


}

    /**
     * Muestra el formulario para crear un nuevo libro.
     */

    public function create()
    {
        return view('books.create');
    }

    /**
     * Guarda un nuevo libro en la base de datos.
     */

    public function store(Request $request)
    {
        // Validamos que los datos sean correctos según nuestras reglas
        $validated = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|unique:books,isbn',
            'pages' => 'required|integer|min:1',
            'genre' => 'nullable|string',
            'cover_url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1000|max:2100',
        ]);

        // Creamos el libro usando el método 'create' (gracias al $fillable que pusimos antes)
        Book::create($validated);

        // Redirigimos al listado con un mensaje de éxito
        return redirect()->route('books.index')->with('success', '¡Libro añadido correctamente!');
    }

    /**
     * Muestra el formulario para editar el libro.
     */

    public function edit(Book $book) {
            return view('books.edit', compact('book'));
    }

    /**
     * Actualiza el libro en la base de datos.
     */

    public function update(Request $request, Book $book) {  
    
    // Validación: aquí está el truco del ISBN.
        // Le decimos que sea único pero que ignore el ID de este libro actual.

        $validated = $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|unique:books,isbn,' . $book->id,
            'pages' => 'required|integer|min:1',
            'genre' => 'nullable|string',
            'cover_url' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'published_year' => 'nullable|integer|min:1000|max:2100',
        ]);

        $book->update($validated);

        return redirect()->route('books.index')->with('success', '¡Libro actualizado correctamente!');
    }

    /**
     * Elimina el libro de la base de datos.
     */

    public function destroy(Book $book) {
        $book->delete();
        
        return redirect()->route('books.index')->with('success', 'Libro eliminado con éxito.');
    }

    /**
     * Muestra la página de detalles del libro con reseñas
     * GET /books/{book}
     */
    public function show(Book $book) {

        // Obtenemos las reseñas del libro, ordenadas por más recientes primero
        $reviews = $book -> reviews() 
                        -> with('user') 
                        -> orderBy('created_at', 'desc') 
                        -> get();

        // Calculamr rating promedio
        $averageRating = $reviews -> avg('rating');

        // Verificar si el usuario actual ya reseño este libro
        $userReview = null;
        if (auth() -> check()) {
            $userReview = Review::where('user_id', auth() -> id())
                            -> where('book_id', $book -> id)
                            -> first();
        }

        // Verificar si el libro está en la libreróa deñ usuario 
        $inlibrary = auth() -> check() 
                    ? auth() -> user() -> books() -> where('book_id', $book -> id) -> exists()
                    : false;

        return view('books.show', [
            'book' => $book,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'userReview' => $userReview,
            'inLibrary' => $inlibrary,
        ]);
    }
}
