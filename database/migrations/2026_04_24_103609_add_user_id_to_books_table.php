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
        Schema::table('books', function (Blueprint $table) {
            // Añadimos user_id después del id, con FK hacia users
            // nullableForeignId porque los libros existentes aún no tienen usuario.
            $table -> foreignId('user_id') -> nullable() -> after('id') -> constrained() -> onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Primero hay que eliminar la FK antes de borrar la columna
            // Si intentamos dropColumn sin dropForeign primero, MySQL lanzará un error
            $table -> dropForeign(['user_id']);
            $table -> dropColumn('user_id');
        });
    }
};
