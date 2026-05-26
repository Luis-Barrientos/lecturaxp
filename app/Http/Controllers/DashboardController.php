<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\XPCalculator;

class DashboardController extends Controller
{
    public function index(Request $request) {

        //Obtenemos el usuario autenticado
        $user = $request->user();

        // Intanciamos el calculador para saber el XP necesario para el siguiente nivel
        $calculator = new XPCalculator();

        // XP necesario para llegar al nivel actual y al siguiente
        // Esto nos permite calcular el porcentaje de progreso de la barra
        $xpCurrentLevel = $calculator->xpForLevel($user->current_level);
        $xpNextLevel = $calculator->xpForLevel($user->current_level + 1); 

        // Progreso dentro del nivel actual (0 a 100)
        // Ejenpplo: si necesitas 100 XP para el nivel 2 y tienes 60, estás a al 60%
        $xpProgress = $xpNextLevel > $xpCurrentLevel
            ? round((($user->total_xp - $xpCurrentLevel) / ($xpNextLevel - $xpCurrentLevel)) * 100)
            : 100;

            // Última 5 sesiones de lectura del usuario, con el libro relacionado
            // Usamos 'with' para evitar el problema N+1 (una consulta en vez de muchas)
            $recentLogs = $user->readingLogs()
                            ->with('book')
                            ->orderBy('date', 'desc')
                            ->limit(5)
                            ->get();     
            return view('dashboard', compact(
                'user',
                'xpCurrentLevel',
                'xpNextLevel',
                'xpProgress',
                'recentLogs'
            ));
    }
}
