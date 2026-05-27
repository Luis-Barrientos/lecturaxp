<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class MakeUserAdmin extends Seeder
{
    /**
     * Convierte al usuario 'Test User' o el primero en admin para acceso al panel admin
     */
    public function run(): void
    {
        // Busca al usuario 'Test User' y lo hace admin, o usa el primero
        $user = User::where('name', 'Test User')->first() ?? User::first();
        
        if ($user) {
            $user->role = 'admin';
            $user->save();
            $this->command->info("✅ Usuario '{$user->name}' (ID: {$user->id}) es ahora admin");
        } else {
            $this->command->warn("❌ No hay usuarios en la base de datos");
        }
    }
}
