<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CoordinatesDetector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $coordinates = [
            'latitude' => 40.6449329,
            'longitude' => 22.9416259
        ];

        if ($request->hasHeader('X-Location')) {
            $location = explode(',', $request->header('X-Location'));
            $coordinates = [
                'latitude' => $location[0],
                'longitude' => $location[1]
            ];
        }

        $request->merge(['coordinates' => $coordinates]);

        return $next($request);
    }
}
