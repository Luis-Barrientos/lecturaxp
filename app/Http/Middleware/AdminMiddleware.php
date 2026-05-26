<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Comprobamos que el usuario esté autenticado Y que su rol sea 'admin'
        // Si no cumple alguna de las dos condiciones, lo redirigimos a dashboard
        // con un mensaje de error informativo
        if (!auth() -> check() || auth() -> user() -> role !== 'admin') {
            return redirect() -> route('dashboard')
                -> with('error', 'No tienes permiso para acceder a esta sección.');
        }
        return $next($request);
    }
}
