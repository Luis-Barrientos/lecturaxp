<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ReadingLog extends Model
{
    use HasFactory;

    //Campos que se pueden rellenar masivamente
    protected $fillable =[
         'user_id',
         'book_id',
         'date',
         'pages_read',
         'comments',
         'xp_earned',
         'is_completed',
    ];

    /**
     *Un regustro de lectura pertenece a un usuario.
     */

     public function user() {
        return $this->belongsTo(User::class);
     }

     /**
      * Un reguistro de lectura pertenece a un libro.
      */

     public function book() {
        return $this->belongsTo(Book::class);
     }

}
