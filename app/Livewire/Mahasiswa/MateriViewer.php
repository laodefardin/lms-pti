<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\{Layout, On};
use App\Models\{Kelas, KontenMateri, MateriProgress, CatatanMahasiswa};
use App\Services\{GamifikasiService, NilaiService};
use Illuminate\Support\Facades\Auth;

// Viewer punya layout sendiri (fullscreen, tanpa sidebar utama)
class MateriViewer extends Component
{
    public Kelas $kelas;
    public KontenMateri $konten;

    public bool $sidebarOpen  = true;
    public bool $notesOpen    = true;
    public string $catatan    = '';
    public bool $saved        = false;

    public function mount(string $slug, KontenMateri $konten)
    {
        // Resolve kelas dari slug atau ID
        $kelas = \App\Models\Kelas::with('mataKuliah')
            ->get()
            ->first(fn($k) => $k->slug === $slug || (string) $k->id === $slug);

        abort_if(!$kelas, 404);

        abort_unless(
            $kelas->mahasiswa()->where('mahasiswa_id', Auth::id())->exists(),
            403
        );
        $this->kelas  = $kelas;
        $this->konten = $konten;

        // Load catatan existing
        $existing = CatatanMahasiswa::where('mahasiswa_id', Auth::id())
            ->where('konten_id', $konten->id)
            ->first();
        $this->catatan = $existing?->isi ?? '';
    }

    public function markSelesai(): void
    {
        MateriProgress::updateOrCreate(
            ['mahasiswa_id' => Auth::id(), 'konten_id' => $this->konten->id],
            ['is_selesai' => true, 'selesai_at' => now()]
        );

        // Award gamifikasi poin (cegah duplikat)
        $gamifikasi = app(GamifikasiService::class);
        $gamifikasi->berikanPoin(
            userId        : Auth::id(),
            tipeAktivitas : \App\Models\GamifikasiPoin::MATERI_SELESAI,
            kelasId       : $this->kelas->id,
            keterangan    : "Materi selesai: {$this->konten->judul}",
            referenceId   : $this->konten->id,
            allowDuplicate: false
        );

        // Update nilai akhir secara async (non-blocking)
        dispatch(function() {
            app(NilaiService::class)->hitungNilaiAkhir(Auth::id(), $this->kelas->id);
        })->afterResponse();

        $this->dispatch('materi-selesai', kontenId: $this->konten->id);
    }

    public function simpanCatatan(): void
    {
        $this->validate(['catatan' => 'nullable|string|max:5000']);

        CatatanMahasiswa::updateOrCreate(
            ['mahasiswa_id' => Auth::id(), 'konten_id' => $this->konten->id],
            ['isi' => $this->catatan]
        );
        $this->saved = true;
        $this->dispatch('catatan-tersimpan');
    }

    public function isSelesai(): bool
    {
        return MateriProgress::where('mahasiswa_id', Auth::id())
            ->where('konten_id', $this->konten->id)
            ->where('is_selesai', true)
            ->exists();
    }

    public function kontenBerikutnya(): ?KontenMateri
    {
        $all = $this->kelas->pertemuan()
            ->with('konten')
            ->get()
            ->flatMap(fn($p) => $p->konten->where('is_published', true))
            ->values();

        $idx = $all->search(fn($k) => $k->id === $this->konten->id);
        return $idx !== false && isset($all[$idx + 1]) ? $all[$idx + 1] : null;
    }

    public function kontenSebelumnya(): ?KontenMateri
    {
        $all = $this->kelas->pertemuan()
            ->with('konten')
            ->get()
            ->flatMap(fn($p) => $p->konten->where('is_published', true))
            ->values();

        $idx = $all->search(fn($k) => $k->id === $this->konten->id);
        return $idx > 0 ? $all[$idx - 1] : null;
    }

    public function render()
    {
        $kelas = $this->kelas->load(['mataKuliah', 'pertemuan.konten']);

        $selesaiIds = MateriProgress::where('mahasiswa_id', Auth::id())
            ->where('is_selesai', true)
            ->pluck('konten_id')
            ->toArray();

        $data = [
            'kelas'      => $kelas,
            'konten'     => $this->konten,
            'selesaiIds' => $selesaiIds,
            'isSelesai'  => $this->isSelesai(),
            'berikutnya' => $this->kontenBerikutnya(),
            'sebelumnya' => $this->kontenSebelumnya(),
            'saved'      => $this->saved,
        ];

        return view('livewire.mahasiswa.materi-viewer', $data)->layout('components.layouts.viewer', $data);
    }
}
