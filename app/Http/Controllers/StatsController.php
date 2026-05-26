<?php

namespace App\Http\Controllers;

use App\Models\ReadingLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index() {

        $user = auth() -> user();

        // Total de páginas leídas en toda la historia del usuario
        $totalPages = $user -> readingLogs() -> sum('pages_read');

        // Contamos los logs que tienen is_completed = true
        $totalBooksCompleted = $user->readingLogs()
            ->where('is_completed', true)
            ->count();

        // XP ganado por mes (últimos 6 meses)
        $xpByMonth = $user -> readingLogs()
            -> selectRaw('to_char("date", \'YYYY-MM\') as month, SUM(xp_earned) as total')
            -> where('date', '>=', Carbon::now()->subMonths(6))
            -> groupBy('month')
            -> orderBy('month')
            -> pluck('total', 'month');    

        // Libros en progreso: tienen sesiones pero ninguna marcada como completada
        $booksInProgress = $user -> readingLogs()
            -> with('book')
            -> select('book_id')
            -> selectRaw('SUM(pages_read) as pages_read_total')
            -> groupBy('book_id')
            -> havingRaw('SUM(is_completed) = 0')
            -> get();    

        // Total de sesiones registradas
        $totalSessions = $user -> readingLogs() -> count();

        //Promedio de páginas por sesion
        $avgPagesPerSession = $totalSessions > 0 
            ? round($totalPages / $totalSessions) : 0;

        // Sesión más productiva (log con más páginas)
        $bestSession = $user->readingLogs()
            ->with('book')
            ->orderByDesc('pages_read')
            ->first();


        // Páginas leídas por mes (ultimos 6 meses)
        // Agrupamos por mes y sumamos las páginas
        $pagesByMonth = $user -> readingLogs()
        ->selectRaw('to_char("date", \'YYYY-MM\') as month, SUM(pages_read) as total')
        ->where('date', '>=', Carbon::now()->subMonths(6))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

        // Comparativa: XP este mes vs mes pasado
        $thisMonthXP = $user->readingLogs()
            ->whereRaw('EXTRACT(MONTH FROM "date") = ?', [now()->month])
            ->whereRaw('EXTRACT(YEAR FROM "date") = ?', [now()->year])
            ->sum('xp_earned');
        $lastMonthXP = $user->readingLogs()
            ->whereRaw('EXTRACT(MONTH FROM "date") = ?', [now()->subMonth()->month])
            ->whereRaw('EXTRACT(YEAR FROM "date") = ?', [now()->subMonth()->year])
            ->sum('xp_earned');
        $xpDiff = $lastMonthXP > 0 ? round((($thisMonthXP - $lastMonthXP) / $lastMonthXP) * 100) : 0;

        // Velocidad de lectura (págs/día promedio)
        $totalDays = $user->readingLogs()->selectRaw('COUNT(DISTINCT "date"::date) as days')->first()->days ?? 0;
        $avgPagesPerDay = $totalDays > 0 ? round($totalPages / $totalDays, 1) : 0;

        // Géneros favoritos
        $favoriteGenres = $user->readingLogs()
            ->with('book')
            ->get()
            ->groupBy('book.genre')
            ->map(fn($logs) => ['genre' => $logs[0]->book->genre, 'count' => $logs->count()])
            ->sortByDesc('count')
            ->take(5);

        // Autores más leídos
        $favoriteAuthors = $user->readingLogs()
            ->with('book')
            ->get()
            ->groupBy('book.author')
            ->map(fn($logs) => ['author' => $logs[0]->book->author, 'count' => $logs->count()])
            ->sortByDesc('count')
            ->take(5);

        return view('stats.index', compact(
            'totalPages',
            'totalBooksCompleted',
            'totalSessions',
            'pagesByMonth',
            'avgPagesPerSession',
            'bestSession',
            'xpByMonth',
            'booksInProgress',
            'xpDiff',
            'avgPagesPerDay',
            'favoriteGenres',
            'favoriteAuthors',
            'thisMonthXP',
            'lastMonthXP'
            ));
        }
}
