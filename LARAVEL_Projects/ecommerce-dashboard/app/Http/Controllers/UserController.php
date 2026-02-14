<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::where('tenant_id', auth()->user()->tenant_id)->where('role', '!=', 'customer')->orderBy('created_at', 'desc')->paginate(10);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'users' => $users,
            ]);
        } else {
            return Inertia::render('Users/Index', [
                'users' => $users,
            ]);
        }
    }

    public function create()
    {
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([]);
        } else {
            return Inertia::render('Users/Create');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:owner,cashier'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'tenant_id' => auth()->user()->tenant_id,
            'email_verified_at' => now(),
        ]);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['user' => $user, 'message' => 'Staff baru berhasil ditambahkan.']);
        } else {
            return redirect()->route('users.index')->with('success', 'Staff baru berhasil ditambahkan.');
        }
    }

    public function show(User $user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'user' => $user,
            ]);
        } else {
            return Inertia::render('Users/Show', [
                'user' => $user,
            ]);
        }
    }

    public function update(Request $request, User $user)
    {
        // Jika request API tanpa autentikasi, kembalikan JSON 401
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['user' => $user, 'message' => 'User berhasil diperbarui.']);
        } else {
            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
        }
    }

    public function updateRole(Request $request, User $user)
    {
        // Jika request API tanpa autentikasi, kembalikan JSON 401
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $request->validate([
            'role' => 'required|in:owner,cashier'
        ]);

        if ($user->id === auth()->id() && $request->role !== 'owner') {
            if (request()->is('api/*') || request()->wantsJson()) {
                return response()->json(['error' => 'Anda tidak bisa menurunkan jabatan akun sendiri!'], 400);
            } else {
                return back()->with('error', 'Anda tidak bisa menurunkan jabatan akun sendiri!');
            }
        }

        $user->update(['role' => $request->role]);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['user' => $user, 'message' => 'Peran pengguna berhasil diperbarui.']);
        } else {
            return back()->with('success', 'Peran pengguna berhasil diperbarui.');
        }
    }

    public function destroy(User $user)
    {
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if ($user->id === auth()->id()) {
            if (request()->is('api/*') || request()->wantsJson()) {
                return response()->json(['error' => 'Dilarang menghapus akun sendiri!'], 400);
            } else {
                return back()->with('error', 'Dilarang menghapus akun sendiri!');
            }
        }

        $user->delete();
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['message' => 'User berhasil dihapus.']);
        } else {
            return back()->with('success', 'User berhasil dihapus.');
        }
    }
}
