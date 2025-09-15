<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('firstpage');
        }

        $user = Auth::user();

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }
        // Jika pengguna tidak memiliki salah satu peran yang diberikan
        return abort(403, 'Anda Tidak Memiliki Akses Ke Halaman Ini');
    }
}
