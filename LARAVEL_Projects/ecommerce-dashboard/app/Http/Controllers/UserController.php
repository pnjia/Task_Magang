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

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'in:owner,cashier'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'tenant_id' => auth()->user()->tenant_id,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'Staff baru berhasil ditambahkan.');
        ;
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:owner,cashier'
        ]);

        if ($user->id === auth()->id() && $request->role !== 'owner') {
            return back()->with('error', 'Anda tidak bisa menurunkan jabatan akun sendiri!');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Peran pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Dilarang menghapus akun sendiri!');
        }

        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
