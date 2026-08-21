<?php

namespace App\Livewire\Dosen;

use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use App\Services\{NilaiService, NotifikasiService};
use Livewire\Component;
use Livewire\Attributes\Layout;

class TugasNilai extends Component
{
    public Kelas $kelas;
    public Tugas $tugas;
    
    public $editId = null;
    public $nilai = '';
    public $feedback = '';

    public function mount(Kelas $kelas, Tugas $tugas)
    {
        if ($kelas->dosen_id !== auth()->id() || $tugas->kelas_id !== $kelas->id) {
            abort(403);
        }
        
        $this->kelas = $kelas->load('mahasiswa');
        $this->tugas = $tugas;
    }

    public function openEdit($pengumpulanId)
    {
        $pengumpulan = PengumpulanTugas::find($pengumpulanId);
        if ($pengumpulan && $pengumpulan->tugas_id === $this->tugas->id) {
            $this->editId = $pengumpulanId;
            $this->nilai = $pengumpulan->nilai;
            $this->feedback = $pengumpulan->feedback;
        }
    }

    public function cancelEdit()
    {
        $this->editId = null;
        $this->nilai = '';
        $this->feedback = '';
    }

    public function createAndGrade($mahasiswaId)
    {
        // Pastikan mahasiswa ini benar-benar ada di kelas ini
        if (!$this->kelas->mahasiswa->contains('id', $mahasiswaId)) {
            return;
        }

        // Buat record kosong agar bisa dinilai
        $pengumpulan = PengumpulanTugas::firstOrCreate([
            'tugas_id' => $this->tugas->id,
            'mahasiswa_id' => $mahasiswaId,
        ], [
            'status' => 'dikirim',
            'nilai' => 0,
        ]);

        $this->openEdit($pengumpulan->id);
    }

    public function saveNilai()
    {
        $this->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string'
        ]);

        $pengumpulan = PengumpulanTugas::find($this->editId);
        if ($pengumpulan && $pengumpulan->tugas_id === $this->tugas->id) {
            $pengumpulan->update([
                'nilai'      => $this->nilai,
                'feedback'   => $this->feedback,
                'dinilai_at' => now(),
                'status'     => 'dinilai',
            ]);

            // Auto-hitung nilai akhir mahasiswa ini
            dispatch(function() use ($pengumpulan) {
                app(NilaiService::class)->hitungNilaiAkhir(
                    $pengumpulan->mahasiswa_id,
                    $this->tugas->kelas_id
                );
            })->afterResponse();

            // Notifikasi ke mahasiswa
            app(NotifikasiService::class)->kirim(
                userId : $pengumpulan->mahasiswa_id,
                tipe   : 'nilai',
                judul  : '📊 Tugas Sudah Dinilai!',
                pesan  : "Tugas \"" . $this->tugas->judul . "\" kamu sudah dinilai. Nilai: {$this->nilai}/100",
                icon   : '📊',
                link   : "/mahasiswa/tugas/{$this->tugas->id}"
            );
        }

        $this->cancelEdit();
        session()->flash('success', 'Nilai berhasil disimpan.');
    }

    #[Layout('components.layouts.dosen', ['title' => 'Penilaian Tugas'])]
    public function render()
    {
        $pengumpulans = PengumpulanTugas::with('mahasiswa')
            ->where('tugas_id', $this->tugas->id)
            ->get();
            
        $mahasiswaIds = $pengumpulans->pluck('mahasiswa_id')->toArray();
        
        $belumMengumpulkan = $this->kelas->mahasiswa->whereNotIn('id', $mahasiswaIds);

        return view('livewire.dosen.tugas-nilai', [
            'pengumpulans' => $pengumpulans,
            'belumMengumpulkan' => $belumMengumpulkan
        ]);
    }
}
