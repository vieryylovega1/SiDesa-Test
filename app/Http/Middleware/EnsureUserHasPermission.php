<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active || ! $user->canAccess($permission)) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}
