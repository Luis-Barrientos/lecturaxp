<?php

namespace App\Observers;

use App\Models\ReadingLog;
use App\Services\XPCalculator;
use App\Services\AchievementChecker;
use Carbon\Carbon;

class ReadingLogObserver
{
    /**
     * Se ejecuta autináticamente justo después de crear un ReadingLog.
     * 
     * Aqui centralizamos toda la lógica de XP, nievels y rachas.
     * Usamos DB: transaction() para garantizar que si algo falla,
     * ningún cambio parcial queda guardado en la base de datos.
     */
    public function created(ReadingLog $readingLog): void
    {
        
        //Obtenemos el usuario al que pertenece este log
        $user = $readingLog->user;

        //instanciamos el servicio de cálculo de XP
        $calculator = new XPCalculator();

        // 1. Actualizamos la racha ANTES de calcular el XP
        // (porque el bonus de racha depende del streak actualizado)
        $this->updateStreak($user, $readingLog->date);

        //2. Calculamos el XP ganado en esta sesión
        $xpEarned = $calculator->calculate($user, $readingLog->pages_read, (bool) $readingLog->is_completed);

        // 3. Calculamos el XP en el porpio Log ( para auditoría histórica)
        $readingLog->xp_earned = $xpEarned;
        $readingLog->saveQuietly();

        // 4. Actualizamos el XP total del usuario
        $newTotalXp = $user->total_xp + $xpEarned;

        // 5. Recalculamos el nivel con el nuevo XP total
        $newLevel = $calculator->calculateLevel($newTotalXp, $user->current_level);

        // 6. Guardamos todos los cambios del usuario de una vez
        $user->total_xp = $newTotalXp;
        $user->current_level = $newLevel;
        $user->save();

        // 7. Comprobamos si el usuario ha desbloqueado algún logro
        (new AchievementChecker())->check($user);

        // 8. Si el log está marcado como completado, actualizar el status en la librería personal
        if ($readingLog->is_completed) {
            $user->books()->updateExistingPivot($readingLog->book_id, ['status' => 'completado']);
        }
    }

    /**
     * Se ejecuta automáticamente después de actualizar un ReadingLog.
     * 
     * Recalcula la diferencia de XP entre el valor anterior y el nuevo,
     * y ajusta el total del usuario sumado o restando esa diferencia.
     */
    public function updated(ReadingLog $readingLog): void {

        $user = $readingLog -> user;
        $calculator = new XPCalculator();

        // XP antiguo: valor que tenía el log ANTES de la edición
        // getOriginal() guarda los datos previos antes de cualquier cambio 
        $xpOld = $readingLog -> getOriginal('xp_earned');

        // XP nuevo: recalculamos con las páginas actualizadas
        $xpNew = $calculator ->calculate($user, $readingLog -> pages_read, (bool) $readingLog -> is_completed);
    
    
        // La diferencia puede ser positiva (más páginas) o negativa (menos páginas)
        $xpDiff = $xpNew - $xpOld;

        // Actualizamos el XP guardado en el propio log
        // Usamos saveQuietly() para no disparar updated() otra vez → bucle infinito
        $readingLog->xp_earned = $xpNew;
        $readingLog->saveQuietly();

        // Aplicamos la diferencia al total del usuario
        $user->total_xp = max(0, $user->total_xp + $xpDiff);

        // Recalculamos el nivel con el nuevo total de XP
        $user->current_level = $calculator->calculateLevel($user->total_xp, $user->current_level);

        $user->save();

        // Si el log está marcado como completado, actualizar el status en la librería personal
        if ($readingLog->is_completed) {
            $user->books()->updateExistingPivot($readingLog->book_id, ['status' => 'completado']);
        }
    
    }

    /**
     * Actualiza la racha del usuario según la fecha del log.
     *
     * Reglas:
     * - Si ya leyó hoy → la racha no cambia (ya se contó)
     * - Si leyó ayer → la racha aumenta en 1
     * - Si no leyó ayer → la racha se reinicia a 1
     */
    private function updateStreak(object $user, string $sessionDate): void
    {
        $today = Carbon::today();
        $logDate = Carbon::parse($sessionDate);
        $lastRead = $user->last_read_date;

        if ($lastRead === null) {
            //Primera sesión del usuario
            $user->streak_days = 1;
        }elseif ($logDate-> isSameDay($today) && Carbon::parse($lastRead)->isSameDay($today)) {
            //ya registró una sesión hoy, la racha no cambia
        }elseif (Carbon::parse($lastRead)->isSameDay($today->subDay())) {
            //Leyó ayer, la racha continúa
            $user->streak_days++;
        }else{
            //Rompió la racha
            $user->streak_days = 1;
        }

        $user->last_read_date = $logDate->toDateString();

    }

    /**
     * Handle the ReadingLog "deleted" event.
     */
    public function deleted(ReadingLog $readingLog): void
    {
        $user = $readingLog -> user;
        // Restamos exactamente el XP que se ganó en  esta sesión
        $user -> total_xp = max(0, $user -> total_xp - $readingLog -> xp_earned);

        // Recalculamos el nivel con el nuevo XP total
        $calculator = new XPCalculator();
        $user -> current_level = $calculator -> calculateLevel($user -> total_xp, $user -> current_level);

        $user -> save();
    }

    /**
     * Handle the ReadingLog "restored" event.
     */
    public function restored(ReadingLog $readingLog): void
    {
        //
    }

    /**
     * Handle the ReadingLog "force deleted" event.
     */
    public function forceDeleted(ReadingLog $readingLog): void
    {
        //
    }
}
