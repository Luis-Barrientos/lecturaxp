<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $achievements = [
            [
                'name'            => 'Primera sesión',
                'slug'            => 'first_session',
                'description'     => 'Registra tu primera sesión de lectura.',
                'icon'            => '📖',
                'condition_type'  => 'total_logs',
                'condition_value' => 1,
                'xp_reward'       => 50,
            ],
            [
                'name'            => 'Lector habitual',
                'slug'            => 'ten_sessions',
                'description'     => 'Registra 10 sesiones de lectura.',
                'icon'            => '📚',
                'condition_type'  => 'total_logs',
                'condition_value' => 10,
                'xp_reward'       => 100,
            ],
            [
                'name'            => 'Racha de fuego',
                'slug'            => 'streak_7',
                'description'     => 'Mantén una racha de 7 días seguidos.',
                'icon'            => '🔥',
                'condition_type'  => 'streak_days',
                'condition_value' => 7,
                'xp_reward'       => 150,
            ],
            [
                'name'            => 'Imparable',
                'slug'            => 'streak_30',
                'description'     => 'Mantén una racha de 30 días seguidos.',
                'icon'            => '⚡',
                'condition_type'  => 'streak_days',
                'condition_value' => 30,
                'xp_reward'       => 500,
            ],
            [
                'name'            => 'Centurión',
                'slug'            => 'xp_1000',
                'description'     => 'Acumula 1.000 puntos de XP.',
                'icon'            => '⭐',
                'condition_type'  => 'total_xp',
                'condition_value' => 1000,
                'xp_reward'       => 200,
            ],
            [
                'name'            => 'Primer libro',
                'slug'            => 'first_book',
                'description'     => 'Completa tu primer libro.',
                'icon'            => '🏆',
                'condition_type'  => 'completed_books',
                'condition_value' => 1,
                'xp_reward'       => 200,
            ],
            [
                'name'            => 'Devorador de libros',
                'slug'            => 'five_books',
                'description'     => 'Completa 5 libros.',
                'icon'            => '🎯',
                'condition_type'  => 'completed_books',
                'condition_value' => 5,
                'xp_reward'       => 500,
            ],

        ];

        foreach ($achievements as $achievement) {
            Achievement::firstOrCreate(
                ['slug' => $achievement['slug']],
                $achievement
            );
        }
    } 
}