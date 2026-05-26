<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_book', function (Blueprint $table) {
            $table -> id();
            // Clave foranea hacia la tabla users
            $table -> foreignId('user_id') -> constrained() -> onDelete('cascade');
            // Clave foranea hacia la tabla books
            $table -> foreignId('book_id') -> constrained() -> onDelete('cascade');
            $table -> string('status') -> default('leyendo');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_book');
    }
};
