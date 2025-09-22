<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ClientKey;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('php-auth-user');
        $secret = $request->header('php-auth-pw');

        $client = ClientKey::where('key', $key)->where('secret', $secret)->first(); 
        
        if($client) {
            return $next($request);
        }

        return abort(403);
    }
}
