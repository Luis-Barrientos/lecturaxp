<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ReadingLogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\UserLibraryController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas para el perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para el catálogo de libros
    // Las rutas de admin (create, edit, etc.) van PRIMERO para que /books/create
    // no sea capturado por /books/{book} antes de llegar al método correcto
    Route::middleware('admin')->group(function () {
        Route::resource('books', BookController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);
    });
    Route::resource('books', BookController::class)->only(['index', 'show']);
    Route::get('/logros', [AchievementController::class, 'index'])->name('achievements.index');
    Route::get('/estadisticas', [StatsController::class, 'index'])->name('stats.index');
    Route::resource('books.reading-logs', ReadingLogController::class)->only(['index', 'create', 'store', 'destroy', 'edit', 'update']);
    Route::get('/libreria', [UserLibraryController::class, 'index'])->name('library.index');
    Route::post('/libreria/{book}', [UserLibraryController::class, 'store'])->name('library.store');
Route::delete('/libreria/{book}', [UserLibraryController::class, 'destroy'])->name('library.destroy');

     // Rutas para reseñas
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Rutas del panel de administración
    // Protegidas por dos middlewares: 'auth' (debe estar logeado) y 'admin' (debe ser administrador)
    Route::middleware(['auth', 'admin']) -> prefix('admin') -> name('admin.') -> group(function() {
        
        //panel principal - resumen general
        Route::get('/', [AdminController::class, 'index']) -> name('index');
        
        // Gestion de usuarios
        Route::get('/usuarios', [AdminController::class, 'users']) -> name('users');
        Route::patch('/usuarios/{user}/role', [AdminController::class, 'updateRole']) -> name('users.role');

        // Gestión de libros del catálogo
        Route::get('/libros', [AdminController::class, 'books']) -> name('books');
        Route::delete('/libros/{book}', [AdminController::class, 'destroyBook']) -> name('books.destroy');

        // Rutas para importar libros desde Open Library o en lote
        Route::get('/libros/importar', [AdminController::class, 'importBooksForm'])->name('books.import-form');
        Route::get('/libros/buscar-ol', [AdminController::class, 'searchBooksOpenLibrary'])->name('books.search-ol');
        Route::post('/libros/importar-ol', [AdminController::class, 'importBookFromOpenLibrary'])->name('books.import-ol');
        Route::post('/libros/importar-lote', [AdminController::class, 'bulkImportBooks'])->name('books.bulk-import');
        
        // Gestión de logros
        Route::get('/logros', [AdminController::class, 'achievements'])->name('achievements');
        Route::get('/logros/crear', [AdminController::class, 'createAchievement'])->name('achievements.create');
        Route::post('/logros', [AdminController::class, 'storeAchievement'])->name('achievements.store');
        Route::get('/logros/{achievement}/editar', [AdminController::class, 'editAchievement'])->name('achievements.edit');
        Route::patch('/logros/{achievement}', [AdminController::class, 'updateAchievement'])->name('achievements.update');
        Route::delete('/logros/{achievement}', [AdminController::class, 'destroyAchievement'])->name('achievements.destroy');
    });

});


require __DIR__.'/auth.php';