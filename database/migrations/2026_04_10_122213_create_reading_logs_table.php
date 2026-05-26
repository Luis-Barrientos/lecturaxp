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
        Schema::create('reading_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relación con el usuario: usamos foreignId para que Laravel sepa que es una clave foránea.
            $table->foreignId('book_id')->constrained()->onDelete('cascade'); // Relación con el libro: mismo concepto que antes.
            $table->date('date'); // Fecha de la sesión de lectura.
            $table->integer('pages_read'); // Páginas que hemos leído en esta sesión.
            $table->text('comments')->nullable(); // Comentarios (opcional, por eso usamos nullable)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_logs');
    }
};
