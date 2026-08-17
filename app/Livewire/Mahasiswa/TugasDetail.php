<?php

namespace App\Livewire\Mahasiswa;

use App\Models\Tugas;
use App\Models\PengumpulanTugas;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Services\GamifikasiService;

#[Layout('components.layouts.mahasiswa', ['title' => 'Detail Tugas'])]
class TugasDetail extends Component
{
    use WithFileUploads;

    public Tugas $tugas;
    public $pengumpulan;
    
    public $fileUpload;
    public $linkUrl;
    public $catatan;
    public $showSuccess = false;

    public function mount(Tugas $tugas)
    {
        $user = Auth::user();
        
        $isEnrolled = $tugas->kelas->mahasiswa()->where('mahasiswa_id', $user->id)->exists();
        if (!$isEnrolled) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        $this->tugas = $tugas;
        $this->pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('mahasiswa_id', $user->id)
            ->first();

        if ($this->pengumpulan) {
            $this->linkUrl = $this->pengumpulan->link_url;
            $this->catatan = $this->pengumpulan->catatan_mahasiswa;
        }
    }

    public function kumpulkan()
    {
        $this->validate([
            'fileUpload' => 'nullable|file|max:10240',
            'linkUrl' => 'nullable|url',
            'catatan' => 'nullable|string'
        ]);

        if (!$this->fileUpload && !$this->linkUrl && !$this->catatan) {
            $this->addError('general', 'Mohon isi setidaknya salah satu (file, link, atau catatan).');
            return;
        }

        $filePath = $this->pengumpulan ? $this->pengumpulan->file_path : null;

        if ($this->fileUpload) {
            $filePath = $this->fileUpload->store('pengumpulan_tugas', 'public');
        }

        $this->pengumpulan = PengumpulanTugas::updateOrCreate(
            [
                'tugas_id' => $this->tugas->id,
                'mahasiswa_id' => Auth::id(),
            ],
            [
                'file_path' => $filePath,
                'link_url' => $this->linkUrl,
                'catatan_mahasiswa' => $this->catatan,
                'status' => 'dikumpulkan',
                'waktu_pengumpulan' => now(),
            ]
        );

        $gamifikasi = app(GamifikasiService::class);
        $isOntime = now()->lessThanOrEqualTo($this->tugas->deadline);
        
        $gamifikasi->berikanPoin(
            userId: Auth::id(),
            tipeAktivitas: \App\Models\GamifikasiPoin::TUGAS_DIKUMPULKAN,
            kelasId: $this->tugas->kelas_id,
            keterangan: "Pengumpulan tugas: {$this->tugas->judul} (" . ($isOntime ? 'Tepat Waktu' : 'Terlambat') . ")",
            referenceId: $this->tugas->id,
            allowDuplicate: false
        );

        $this->showSuccess = true;
    }

    public function render()
    {
        return view('livewire.mahasiswa.tugas-detail');
    }
}
