<?php

namespace App\Http\Middleware;
use Closure;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Set CORS headers for cross-origin requests
        $response->headers->set('Access-Control-Allow-Origin', '*'); // Adjust as necessary
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        return $response;
    }
     protected $except = [
        //
    ];
}
