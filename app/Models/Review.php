<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable =[
        'user_id',
        'book_id',
        'rating',
        'comment',
    ];

    /**
     * Una reseña pertenece a un usuario.
     */
    public function user() {
        
        return $this -> belongsTo(User::class);
    }

    /**
     * Una reseña pertenece a un libro.
     */
    public function book() {
        return $this -> belongsTo(Book::class);
    }
}   
