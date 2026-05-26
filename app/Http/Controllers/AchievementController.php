<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;


class AchievementController extends Controller
{
    public function index()
    {
        // Todos los logros del juego
        $allAchievements = Achievement::all();

        // Logros desbloqueados del usuario con fecha
        $earnedAchievements = auth()->user()->achievements()
            ->withPivot('earned_at')
            ->get()
            ->keyBy('id');

        return view('achievements.index', compact('allAchievements', 'earnedAchievements'));
    }
}
