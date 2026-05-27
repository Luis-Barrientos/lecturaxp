<?php

use Illuminate\Database\Migrations\Migration;
use Database\Seeders\AchievementSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ejecutar el seeder de logros
        $this->call(AchievementSeeder::class);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No eliminamos los logros al hacer rollback
    }
};
