<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAuthRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        $request->merge([
            'role' => $role
        ]);
        return $next($request);
    }
}
