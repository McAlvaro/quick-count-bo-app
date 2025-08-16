<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        Log::info('User: ' . $user->name);
        Log::info('User: ' . $user->hasRole('super_admin'));

        if (! $user || !$user->hasRole('super_admin')) {
            abort(403, 'No tienes acceso al panel de administración.');
        }

        return $next($request);
    }
}
