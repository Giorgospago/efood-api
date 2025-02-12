<?php

namespace App\Http\Middleware;

use App\Enum\RoleCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $user = auth()->user();
        $role = $user->roles()->where('role_id', RoleCode::{$role})->first();

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return $next($request);
    }
}
