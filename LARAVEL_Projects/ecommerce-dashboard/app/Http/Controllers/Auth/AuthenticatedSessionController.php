<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response|JsonResponse
    {
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'canResetPassword' => true,
                'status' => session('status'),
            ]);
        }

        return Inertia::render('Auth/Login', [
            'canResetPassword' => true,
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse|JsonResponse
    {
        $request->authenticate();

        if ($request->wantsJson()) {
            $user = $request->user();
            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Login successful',
            ]);
        }

        $request->session()->regenerate();

        $role = $request->user()->role;

        if ($role === 'owner') {
            return redirect()->route('dashboard');
        }

        if ($role === 'cashier') {
            return redirect()->route('transactions.create');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->wantsJson()) {
            // API logout: just revoke the current token
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully']);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
