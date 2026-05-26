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
            //Primero eliminamios la clave foránea (el índice en la BD)
            //Si intentamos borrar la columna sin hacer esto antes, MySQL dará error
            //El nombre del índice sigue el patrón: tabla_columna_foreign
            $table -> dropForeign(['user_id']);

            // Ahora eliminamos la columna user_id de la tabla books
            $table -> dropColumn('user_id');

            //eliminamos status porque ahora estaran en user_book
            $table -> dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Volvemos a añadir user_id como clave foránea haca users
            $table -> foreignId('user_id') -> nullable() -> after('id') -> constrained() -> onDelete('cascade');

            //Volvemos a ñadir status con sus valores posibles
            $table -> string('status') -> default('leyendo');
        });
    }
};
