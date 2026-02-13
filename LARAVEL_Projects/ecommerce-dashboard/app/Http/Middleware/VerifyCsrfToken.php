<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'login',
        'register',
        'api/login',
        'api/register',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug: log request details to help diagnose 419
        try {
            Log::debug('VerifyCsrfToken start', [
                'path' => $request->path(),
                'fullUrl' => $request->fullUrl(),
                'method' => $request->method(),
                'wantsJson' => $request->wantsJson(),
                'is_login' => $request->is('login'),
                'is_register' => $request->is('register'),
                'is_api_login' => $request->is('api/login'),
                'is_api_register' => $request->is('api/register'),
            ]);
        } catch (\Throwable $e) {
            // ignore logging failures
        }

        // Skip CSRF for login and register routes
        if ($request->is('login') || $request->is('register') || $request->is('api/login') || $request->is('api/register')) {
            Log::debug('VerifyCsrfToken skip: explicit route match', ['path' => $request->path()]);
            return $next($request);
        }

        // Skip CSRF for API requests (JSON or api/* routes)
        if ($request->wantsJson() || $request->is('api/*')) {
            Log::debug('VerifyCsrfToken skip: json or api/*', ['path' => $request->path()]);
            return $next($request);
        }

        if (
            $this->isReading($request) ||
            $this->runningUnitTests() ||
            $this->inExceptArray($request) ||
            $this->tokensMatch($request)
        ) {
            try {
                Log::debug('VerifyCsrfToken passed checks', [
                    'inExceptArray' => $this->inExceptArray($request),
                    'tokensMatch' => $this->tokensMatch($request),
                ]);
            } catch (\Throwable $e) {
            }
            return $this->addCookieToResponse($request, $next($request));
        }

        Log::warning('VerifyCsrfToken blocked request - TokenMismatch', [
            'path' => $request->path(),
            'method' => $request->method(),
            'session_token' => $request->session() ? $request->session()->token() : null,
            'request_token' => $this->getTokenFromRequest($request),
        ]);

        throw new \Illuminate\Session\TokenMismatchException('CSRF token mismatch.');
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     */
    protected function inExceptArray(Request $request): bool
    {
        foreach ($this->except as $except) {
            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the request is a read operation.
     */
    protected function isReading(Request $request): bool
    {
        return in_array($request->method(), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the session and input CSRF tokens match.
     */
    protected function tokensMatch(Request $request): bool
    {
        $token = $this->getTokenFromRequest($request);

        return is_string($request->session()->token()) &&
            is_string($token) &&
            hash_equals($request->session()->token(), $token);
    }

    /**
     * Get the CSRF token from the request.
     */
    protected function getTokenFromRequest(Request $request): ?string
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (!$token && $header = $request->header('X-XSRF-TOKEN')) {
            $token = $this->decryptToken($header);
        }

        return $token;
    }

    /**
     * Decrypt the CSRF token from the X-XSRF-TOKEN header.
     */
    protected function decryptToken(string $token): ?string
    {
        try {
            return \Illuminate\Support\Facades\Crypt::decrypt($token, false);
        } catch (\Illuminate\Contracts\Encryption\DecryptException) {
            return null;
        }
    }

    /**
     * Add the CSRF token to the response cookies.
     */
    protected function addCookieToResponse(Request $request, Response $response): Response
    {
        $config = config('session');

        if ($response instanceof \Illuminate\Http\Response) {
            $response->headers->setCookie(
                new \Symfony\Component\HttpFoundation\Cookie(
                    'XSRF-TOKEN',
                    $request->session()->token(),
                    time() + 60 * $config['lifetime'],
                    $config['path'],
                    $config['domain'],
                    $config['secure'],
                    false,
                    false,
                    $config['same_site'] ?? null
                )
            );
        }

        return $response;
    }

    /**
     * Determine if the application is running unit tests.
     */
    protected function runningUnitTests(): bool
    {
        return app()->runningInConsole() && app()->runningUnitTests();
    }
}
