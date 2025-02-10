<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $availableLocaleCodes = array_keys(config('app.available_locales'));
        $locale = config('app.locale');
        
        $locales = explode(",", $request->header('Accept-Language'));
        foreach ($locales as $l) {
            $acceptedLocale = strtolower(substr($l, 0, 2));
            if (in_array($acceptedLocale, $availableLocaleCodes)) {
                $locale = $acceptedLocale;
                break;
            }
        }

        app()->setLocale($locale);
        return $next($request);
    }
}
