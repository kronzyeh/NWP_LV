<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
{
    // Provjeravamo ulogu korisnika
    if (!in_array(auth()->user()->role, $roles)) {
        return abort(403, 'Nemate pravo pristupa ovoj stranici');
    }
    return $next($request);
}

}
