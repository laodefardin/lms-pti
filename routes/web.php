<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Landing Page
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->role . '.dashboard');
    }
    return view('landing');
})->name('home');

/*
|--------------------------------------------------------------------------
| Auth Routes (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

// Redirect setelah login berdasarkan role
Route::get('/dashboard', function () {
    $user = Auth::user();
    return redirect()->route($user->role . '.dashboard');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Mahasiswa Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', \App\Livewire\Mahasiswa\Dashboard::class)->name('dashboard');

        // Matakuliah
        Route::get('/matakuliah', \App\Livewire\Mahasiswa\MatakuliahIndex::class)->name('matakuliah.index');
        Route::get('/matakuliah/{slug}', \App\Livewire\Mahasiswa\MatakuliahDetail::class)->name('matakuliah.detail');

        // Materi Viewer (3-Panel)
        Route::get('/matakuliah/{slug}/materi/{konten}', \App\Livewire\Mahasiswa\MateriViewer::class)->name('materi.viewer');

        // Tugas
        Route::get('/tugas', \App\Livewire\Mahasiswa\TugasIndex::class)->name('tugas.index');
        Route::get('/tugas/{tugas}', \App\Livewire\Mahasiswa\TugasDetail::class)->name('tugas.detail');

        // Kuis & Ujian
        Route::get('/kuis', \App\Livewire\Mahasiswa\KuisIndex::class)->name('kuis.index');
        Route::get('/kuis/{kuis}/mulai', \App\Livewire\Mahasiswa\KuisEngine::class)->name('kuis.engine');
        Route::get('/kuis/{sesi}/hasil', \App\Livewire\Mahasiswa\KuisHasil::class)->name('kuis.hasil');

        // Nilai & Transkrip
        Route::get('/nilai', \App\Livewire\Mahasiswa\NilaiIndex::class)->name('nilai.index');
        Route::get('/nilai/{kelas}', \App\Livewire\Mahasiswa\NilaiDetail::class)->name('nilai.detail');

        // Absensi
        Route::get('/absensi', \App\Livewire\Mahasiswa\AbsensiIndex::class)->name('absensi.index');

        // Forum Diskusi
        Route::get('/forum', \App\Livewire\Mahasiswa\ForumIndex::class)->name('forum.index');
        Route::get('/forum/{kelas}', \App\Livewire\Mahasiswa\ForumKelas::class)->name('forum.kelas');
        Route::get('/forum/thread/{thread}', \App\Livewire\Mahasiswa\ForumThread::class)->name('forum.thread');

        // Kalender
        Route::get('/kalender', \App\Livewire\Mahasiswa\KalenderIndex::class)->name('kalender.index');

        // Leaderboard
        Route::get('/leaderboard', \App\Livewire\Mahasiswa\Leaderboard::class)->name('leaderboard');

        // Profil & Pengaturan
        Route::get('/profil', \App\Livewire\Mahasiswa\Profil::class)->name('profil');
    });

/*
|--------------------------------------------------------------------------
| Dosen Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', \App\Livewire\Dosen\Dashboard::class)->name('dashboard');

        // Matakuliah
        Route::get('/matakuliah', \App\Livewire\Dosen\MatakuliahIndex::class)->name('matakuliah.index');
        Route::get('/matakuliah/buat', \App\Livewire\Dosen\MatakuliahBuat::class)->name('matakuliah.buat');
        Route::get('/matakuliah/{kelas}', \App\Livewire\Dosen\MatakuliahDetail::class)->name('matakuliah.detail');
        Route::get('/matakuliah/{kelas}/edit', \App\Livewire\Dosen\MatakuliahEdit::class)->name('matakuliah.edit');

        // Materi
        Route::get('/matakuliah/{kelas}/materi', \App\Livewire\Dosen\MateriIndex::class)->name('materi.index');
        Route::get('/matakuliah/{kelas}/materi/buat', \App\Livewire\Dosen\MateriBuat::class)->name('materi.buat');
        Route::get('/matakuliah/{kelas}/materi/{pertemuan}/edit', \App\Livewire\Dosen\MateriEdit::class)->name('materi.edit');

        // Tugas
        Route::get('/matakuliah/{kelas}/tugas', \App\Livewire\Dosen\TugasIndex::class)->name('tugas.index');
        Route::get('/matakuliah/{kelas}/tugas/buat', \App\Livewire\Dosen\TugasBuat::class)->name('tugas.buat');
        Route::get('/matakuliah/{kelas}/tugas/{tugas}', \App\Livewire\Dosen\TugasDetail::class)->name('tugas.detail');
        Route::get('/matakuliah/{kelas}/tugas/{tugas}/nilai', \App\Livewire\Dosen\TugasNilai::class)->name('tugas.nilai');

        // Kuis & Ujian
        Route::get('/matakuliah/{kelas}/kuis', \App\Livewire\Dosen\KuisIndex::class)->name('kuis.index');
        Route::get('/matakuliah/{kelas}/kuis/buat', \App\Livewire\Dosen\KuisBuat::class)->name('kuis.buat');
        Route::get('/matakuliah/{kelas}/kuis/{kuis}', \App\Livewire\Dosen\KuisDetail::class)->name('kuis.detail');
        Route::get('/matakuliah/{kelas}/bank-soal', \App\Livewire\Dosen\BankSoal::class)->name('bank-soal');

        // Absensi
        Route::get('/matakuliah/{kelas}/absensi', \App\Livewire\Dosen\AbsensiIndex::class)->name('absensi.index');

        // Nilai Akhir
        Route::get('/matakuliah/{kelas}/nilai', \App\Livewire\Dosen\NilaiIndex::class)->name('nilai.index');

        // Forum
        Route::get('/matakuliah/{kelas}/forum', \App\Livewire\Dosen\ForumIndex::class)->name('forum.index');

        // Pengumuman
        Route::get('/pengumuman', \App\Livewire\Dosen\PengumumanIndex::class)->name('pengumuman.index');

        // Analitik
        Route::get('/matakuliah/{kelas}/analitik', \App\Livewire\Dosen\Analitik::class)->name('analitik');

        // Profil
        Route::get('/profil', \App\Livewire\Dosen\Profil::class)->name('profil');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');

        // Pengguna
        Route::get('/mahasiswa', \App\Livewire\Admin\MahasiswaIndex::class)->name('mahasiswa.index');
        Route::get('/dosen', \App\Livewire\Admin\DosenIndex::class)->name('dosen.index');
        Route::get('/admin-users', \App\Livewire\Admin\AdminIndex::class)->name('admin.index');

        // Akademik
        Route::get('/semester', \App\Livewire\Admin\SemesterIndex::class)->name('semester.index');
        Route::get('/mata-kuliah', \App\Livewire\Admin\MataKuliahIndex::class)->name('mata-kuliah.index');
        Route::get('/kelas', \App\Livewire\Admin\KelasIndex::class)->name('kelas.index');

        // Kalender & Pengumuman
        Route::get('/kalender', \App\Livewire\Admin\KalenderIndex::class)->name('kalender.index');
        Route::get('/pengumuman', \App\Livewire\Admin\PengumumanIndex::class)->name('pengumuman.index');

        // Laporan
        Route::get('/laporan', \App\Livewire\Admin\LaporanIndex::class)->name('laporan.index');

        // Pengaturan
        Route::get('/pengaturan', \App\Livewire\Admin\PengaturanIndex::class)->name('pengaturan.index');

        // Audit Log
        Route::get('/audit-log', \App\Livewire\Admin\AuditLog::class)->name('audit-log');
    });
