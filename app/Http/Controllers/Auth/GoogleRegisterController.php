<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleRegisterController extends Controller
{
    /**
     * Tampilkan form pendaftaran Google (pilih role + input NIM/NIP).
     */
    public function show()
    {
        // Pastikan ada data Google di session
        if (!session('google_user')) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Sesi Google tidak ditemukan. Silakan coba login ulang.']);
        }

        return view('auth.google-register', [
            'googleUser' => session('google_user'),
        ]);
    }

    /**
     * Proses pendaftaran: validasi NIM/NIP dan hubungkan akun Google.
     */
    public function store(Request $request)
    {
        // Pastikan ada data Google di session
        if (!session('google_user')) {
            return redirect()->route('login')
                ->withErrors(['google' => 'Sesi Google tidak ditemukan. Silakan login Google ulang.']);
        }

        $request->validate([
            'role'    => ['required', 'in:mahasiswa,dosen'],
            'nim_nip' => ['required', 'string', 'max:50'],
        ], [
            'role.required'    => 'Pilih peran Anda (Mahasiswa atau Dosen).',
            'nim_nip.required' => 'NIM / NIP wajib diisi.',
        ]);

        $googleData = session('google_user');
        $role       = $request->role;
        $nimNip     = trim($request->nim_nip);

        // Cari user yang sudah didaftarkan admin berdasarkan nim_nidn dan role
        $user = User::where('nim_nidn', $nimNip)
                    ->where('role', $role)
                    ->first();

        if (!$user) {
            $label = $role === 'mahasiswa' ? 'NIM' : 'NIP/NIDN';
            return back()->withErrors([
                'nim_nip' => "{$label} '{$nimNip}' tidak ditemukan atau tidak sesuai dengan peran yang dipilih. Pastikan {$label} sudah didaftarkan oleh Admin.",
            ])->withInput();
        }

        // Cek apakah akun ini sudah terhubung ke Google lain
        if ($user->google_id && $user->google_id !== $googleData['id']) {
            return back()->withErrors([
                'nim_nip' => 'Akun ini sudah terhubung ke akun Google lain. Hubungi Administrator.',
            ])->withInput();
        }

        // Hubungkan akun Google ke user yang ditemukan
        $user->update([
            'google_id'     => $googleData['id'],
            'google_avatar' => $googleData['avatar'],
            // Update nama jika belum diisi
            'name'          => $user->name ?: $googleData['name'],
            // Update email jika belum diisi
            'email'         => $user->email ?: $googleData['email'],
        ]);

        // Hapus session Google
        session()->forget('google_user');

        // Login
        Auth::login($user, true);

        return redirect()->route($user->role . '.dashboard')
            ->with('success', 'Akun berhasil dihubungkan dengan Google! Selamat datang, ' . $user->name . '.');
    }
}
