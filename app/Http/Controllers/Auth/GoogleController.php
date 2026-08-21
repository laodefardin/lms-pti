<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Arahkan user ke halaman login Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Tangani callback dari Google setelah login.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Login Google gagal. Silakan coba lagi.']);
        }

        // 1. Cek apakah sudah ada akun dengan google_id ini
        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            // Sudah terhubung — langsung login
            Auth::login($user, true);
            return redirect()->route($user->role . '.dashboard');
        }

        // 2. Cek apakah email sudah terdaftar (akun lama, belum dihubungkan ke Google)
        $userByEmail = User::where('email', $googleUser->getEmail())->first();

        if ($userByEmail) {
            // Hubungkan akun Google ke akun yang sudah ada
            $userByEmail->update([
                'google_id'     => $googleUser->getId(),
                'google_avatar' => $googleUser->getAvatar(),
            ]);
            Auth::login($userByEmail, true);
            return redirect()->route($userByEmail->role . '.dashboard');
        }

        // 3. Akun baru — simpan data Google di session, arahkan ke form pendaftaran
        session([
            'google_user' => [
                'id'     => $googleUser->getId(),
                'name'   => $googleUser->getName(),
                'email'  => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ]
        ]);

        return redirect()->route('google.register');
    }
}
