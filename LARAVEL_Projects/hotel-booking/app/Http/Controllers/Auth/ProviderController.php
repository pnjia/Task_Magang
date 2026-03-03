<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    // Mengarahkan user ke halaman login Google
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // Menangani balikan (callback) dari Google
    public function callback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Logic: Cari user berdasarkan provider_id, jika tidak ada, buat baru (updateOrCreate)
            $user = User::updateOrCreate([
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
            ], [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar_url' => $socialUser->getAvatar(),
                // Password kosong, otomatis nullable
                'email_verified_at' => now(), // Anggap email dari Google sudah terverifikasi
            ]);

            Auth::login($user);

            // Redirect ke halaman dashboard
            return redirect()->route('dashboard');

        }
        catch (\Exception $e) {
            // Jika batal login atau error, kembalikan ke halaman login dengan pesan error
            return redirect()->route('login')->withErrors(['oauth' => 'Gagal login menggunakan ' . $provider]);
        }
    }
}