<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Authorize the request when the authenticated user's role is at least
     * the given one (mirrors the legacy "rol >= nivel" threshold model).
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $required = constant(UserRole::class.'::'.$role);

        if (! $request->user()->role->atLeast($required)) {
            abort(403);
        }

        return $next($request);
    }
}
