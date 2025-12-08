<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user || !($user->role && $user->role->nom_role === \App\Enums\RoleEnum::ADMIN)) {
            abort(403, 'Accès réservé aux administrateurs.');
        }
        return $next($request);
    }
}
