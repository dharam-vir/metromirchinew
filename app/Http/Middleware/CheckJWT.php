<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJWT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         try {
            // Attempt to authenticate the user based on the JWT token in the request
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                // If no user is found, return an error response
                return response()->json(['error' => 'User not found'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $e) {
            // If the token is invalid or expired, return an error response
            return response()->json(['error' => 'Token is invalid or expired'], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}

