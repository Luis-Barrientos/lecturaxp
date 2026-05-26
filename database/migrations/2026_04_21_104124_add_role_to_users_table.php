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
        Schema::table('users', function (Blueprint $table) {
            // Añadimos la columna role con dos valores posibles: 'lector' y 'admin'
            // 'lector' es el valor por defecto -> todos los usuarios serán lectores
            // after ('name') la coloca justo después del nombre, por orden lógico
            $table->enum('role', ['visitante', 'lector', 'moderador', 'admin'])
            ->default('lector')
            ->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
