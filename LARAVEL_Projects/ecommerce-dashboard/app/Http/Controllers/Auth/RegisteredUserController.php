<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response|JsonResponse
    {
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([]);
        }

        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'store_name' => ['required', 'string', 'max:255', 'unique:tenants,name'],
            'phone' => ['required', 'string', 'max:20'], // Validasi No HP
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Buat Tenant dengan No HP
        $tenant = Tenant::create([
            'name' => $request->store_name,
            'slug' => Str::slug($request->store_name) . '-' . Str::random(4),
            'phone' => $request->phone, // <--- SIMPAN KE DATABASE (Akan otomatis diformat jadi 62 oleh Model)
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'owner',
            'tenant_id' => $tenant->id,
        ]);

        event(new Registered($user));

        // API path: return token, skip session-based login
        if ($request->wantsJson()) {
            $token = $user->createToken('API Token')->plainTextToken;
            return response()->json([
                'user' => $user,
                'tenant' => $tenant,
                'token' => $token,
                'message' => 'Registration successful',
            ], 201);
        }

        // Web path: session-based login
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
