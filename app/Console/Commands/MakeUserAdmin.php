<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeUserAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:make-admin {email : El email del usuario a hacer admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convierte un usuario a administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("❌ Usuario con email '{$email}' no encontrado");
            return 1;
        }

        $user->role = 'admin';
        $user->save();

        $this->info("✅ Usuario '{$user->name}' ({$user->email}) es ahora admin");
        return 0;
    }
}
