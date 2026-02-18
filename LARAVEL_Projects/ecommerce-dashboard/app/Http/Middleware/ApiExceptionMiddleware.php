<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiExceptionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $e) {
            // Log full exception for developers
            Log::error('Unhandled exception (API): ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // For API requests return a generic 500 message
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json(['message' => 'Internal Server Error'], 500);
            }

            // Re-throw for non-API (let framework handle rendering)
            throw $e;
        }
    }
}
