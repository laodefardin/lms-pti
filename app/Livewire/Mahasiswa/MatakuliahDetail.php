<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{Kelas, MateriProgress};
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Detail Matakuliah'])]
class MatakuliahDetail extends Component
{
    public Kelas $kelas;

    public function mount(string $slug)
    {
        // Cari kelas berdasarkan slug (nama_matakuliah-nama_kelas) atau ID
        $kelasModel = \App\Models\Kelas::with('mataKuliah')
            ->get()
            ->first(function ($k) use ($slug) {
                return $k->slug === $slug || (string) $k->id === $slug;
            });

        abort_if(!$kelasModel, 404);

        // Pastikan mahasiswa ini terdaftar di kelas
        abort_unless(
            $kelasModel->mahasiswa()->where('mahasiswa_id', Auth::id())->exists(),
            403, 'Kamu tidak terdaftar di kelas ini.'
        );
        $this->kelas = $kelasModel;
    }

    public function render()
    {
        $user = Auth::user();
        $kelas = $this->kelas->load([
            'mataKuliah', 'dosen', 'semester',
            'pertemuan.konten',
            'tugas' => fn($q) => $q->where('is_published', true),
            'kuis'  => fn($q) => $q->where('is_published', true),
        ]);

        $progress = $kelas->progressMahasiswa($user->id);

        // Progress per konten
        $selesaiIds = MateriProgress::where('mahasiswa_id', $user->id)
            ->where('is_selesai', true)
            ->pluck('konten_id')
            ->toArray();

        // Tugas pending
        $tugasPending = $kelas->tugas
            ->filter(fn($t) =>
                $t->deadline > now() &&
                !$t->pengumpulan()->where('mahasiswa_id', $user->id)->exists()
            )->count();

        // Kuis aktif
        $kuisAktif = $kelas->kuis
            ->filter(fn($k) => $k->buka_at <= now() && $k->tutup_at >= now())
            ->count();

        return view('livewire.mahasiswa.matakuliah-detail', compact(
            'kelas', 'progress', 'selesaiIds', 'tugasPending', 'kuisAktif'
        ));
    }
}
