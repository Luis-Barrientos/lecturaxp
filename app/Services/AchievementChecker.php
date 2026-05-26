<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;
use Carbon\Carbon;

class AchievementChecker {
    /**
     * Comrpueba todos los logros pendientes de un usuario
     * y otorga los que haya desbloqueado.
     * 
     * Se llama desde el Observer después de cada sesión de lectura.
     */
    public function check(User $user): void {

        // Cargamos todos los logros que el usuario ÁUN NO tiene
        $earnedIds = $user->achievements()->pluck('achievement_id');
        $pending = Achievement::whereNotIn('id', $earnedIds)->get();

        foreach ($pending as $achievement) {
            
            if ($this->isUnlocked($user, $achievement)) {
                $this->grant($user, $achievement);
            }
        }
    }

    /**
     * Comprueba si el usuario cumple la condición de un logro concreto.
     * Usamos un switch por dondition_type para fácilmente extensible.
     */
    private function isUnlocked(User $user, Achievement $achievement): bool {
        
        return match ($achievement->condition_type) {
            'total_logs' => $user->readingLogs()->count() >= $achievement -> condition_value,
            'streak_days' => $user ->streak_days >= $achievement -> condition_value,
            'total_xp' => $user -> total_xp >= $achievement -> condition_value,
            'completed_books' => $user -> readingLogs() -> where('is_completed', true) -> count() >= $achievement -> condition_value,

            default => false,
        };
    }

    /**
     * Otorga el logro al usuario y suma el XP bonus si tiene.
     */
    private function grant(User $user, Achievement $achievement): void {
       
        $user -> achievements() -> attach($achievement -> id, [
            'earned_at' => Carbon::now()
            ]);

            //Si el logro tiene XP de recompensa, lo sumamos al usuario 
            if ($achievement -> xp_reward) {
                $user -> total_xp += $achievement -> xp_reward;
                $user -> save();
            }

           // flash() guarda los datos solo para la SIGUIENTE petición, luego los borra automáticamente
            $current = session('new_achievements', []);
            $current[] = [
                'name' => $achievement->name,
                'icon' => $achievement->icon,
                'xp'   => $achievement->xp_reward,
            ];
            session()->flash('new_achievements', $current);
        }
}