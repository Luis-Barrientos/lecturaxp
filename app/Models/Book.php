<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'pages',
        'genre',
        'cover_url',
        'description',
        'published_year',
    ];

    /**
     * Un libro puede tener muchos registros de lectura.
     */

     public function readingLogs() {
        return $this->hasMany(ReadingLog::class);
     }

    /**
     * Un libro pertenece a muchos usuarios a través de la tabla pivote user_book.
     * Sin esta realción no podriamos hacer $book->user->name en la vsta 
     */
     public function users() {
        //Un libro puede estar en la libreria de muchos usuarios
        return $this -> belongsToMany(User::class)
                     -> withPivot('status')
                     -> withTimestamps();
     }

     /**
      * Un libro tiene muchas reseñas de usuarios.
      */
     public function reviews() {

        return $this -> hasMany(Review::class);
     }
}
