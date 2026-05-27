<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convertir al usuario "Test User" en admin (si existe)
        // Esta migración solo se ejecutará una vez
        DB::table('users')
            ->where('name', 'Test User')
            ->update(['role' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hacemos rollback de esto - es una migración de datos
    }
};
