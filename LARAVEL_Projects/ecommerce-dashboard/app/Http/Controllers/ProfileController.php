<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'user' => $request->user(),
            ]);
        } else {
            return Inertia::render('Profile/Edit', [
                'user' => $request->user(),
            ]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['user' => $request->user(), 'message' => 'Profile updated successfully.']);
        } else {
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => bcrypt($validated['password']),
        ]);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['message' => 'Password updated successfully.']);
        } else {
            return back()->with('status', 'password-updated');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Untuk request API (token-based) jangan panggil Auth::logout() karena
        // guard mungkin RequestGuard (Sanctum) yang tidak punya method logout.
        // Lakukan logout/session invalidation hanya untuk request web.
        $isApi = request()->is('api/*') || request()->wantsJson();

        if (!$isApi) {
            Auth::logout();
        }

        $user->delete();

        if (!$isApi) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['message' => 'Account deleted successfully.']);
        } else {
            return Redirect::to('/');
        }
    }
}
