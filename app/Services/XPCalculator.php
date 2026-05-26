<?php

namespace App\Services;

use App\Models\User;

class XPCalculator
{
    // XP que se gana por cada página leída
    const XP_PER_PAGE = 2;

    // Bonus por completar un libro (marcar como terminado)
    const XP_COMPLETION_BONUS = 50;

    // Escala de bonus por racha — días mínimos => porcentaje de bonus
    const STREAK_BONUSES = [
        365 => 1.00,  // 1 año  → +100%
        30  => 0.40,  // 1 mes  → +40%
        7   => 0.20,  // 1 semana → +20%
        3   => 0.10,  // 3 días  → +10%
    ];

    /**
     * Calcula el XP total que gana el usuario por una sesión de lectura.
     *
     * Ahora acepta un tercer parámetro $isCompleted para el bonus de libro terminado.
     */
    public function calculate(User $user, int $pagesRead, bool $isCompleted = false): int {
        // XP base: páginas × constante
        $xpBase = $pagesRead * self::XP_PER_PAGE;

        // Bonus de racha progresivo
        $streakBonus = $this->getStreakBonus($user->streak_days);
        $xpStreak = (int) round($xpBase * $streakBonus);

        // Bonus por completar el libro
        $xpCompletion = $isCompleted ? self::XP_COMPLETION_BONUS : 0;

        return $xpBase + $xpStreak + $xpCompletion;
    }

    /**
     * Determina el porcentaje de bonus según los días de racha.
     *
     * Recorre la escala de mayor a menor y devuelve el primer bonus
     * que corresponda a la racha actual. Si no llega a 3 días, devuelve 0.
     */
    public function getStreakBonus(int $streakDays): float {
        foreach (self::STREAK_BONUSES as $days => $bonus) {
            if ($streakDays >= $days) {
                return $bonus;
            }
        }

        return 0.0; // Sin bonus si la racha es menor de 3 días
    }

    /**
     * Calcula el XP necesario para alcanzar un nivel determinado.
     * Fórmula: FLOOR(100 × N^1.5)
     */
    public function xpForLevel(int $level): int {
    if ($level <= 1) return 0;
    return (int) floor(100 * pow($level, 1.5));
    }

    /**
     * Devuelve el nivel que corresponde a un XP total acumulado.
     */
    public function calculateLevel(int $totalXp, int $currentLevel): int
    {
        $currentLevel = max(1, $currentLevel); // nunca puede ser menor que 1

        // Bajar nivel si el XP ya no es suficiente para el nivel actual
        while ($currentLevel > 1 && $totalXp < $this->xpForLevel($currentLevel)) {
            $currentLevel--;
        }

        // Subir nivel si el XP es suficiente para el siguiente
        while ($totalXp >= $this->xpForLevel($currentLevel + 1)) {
            $currentLevel++;
        }

        return $currentLevel;
    }
}