<?php
namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        JWTAuth::parseToken()->authenticate();
        return $next($request);
    }
}
