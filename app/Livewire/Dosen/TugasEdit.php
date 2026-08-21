<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\{Kelas, Tugas};
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.dosen', ['title' => 'Edit Tugas'])]
class TugasEdit extends Component
{
    use WithFileUploads;

    public Kelas $kelas;
    public Tugas $tugas;
    
    public string  $judul       = '';
    public string  $deskripsi   = '';
    public string  $tipe        = 'file';  // file|link|file_link
    public ?string $deadline    = null;
    public int     $bobotNilai  = 100;
    public int     $maxFileSize = 10;        // MB
    public array   $allowedExt  = ['pdf', 'docx', 'zip'];
    public bool    $isPublished = false;
    public $fileSoal            = null;

    public function mount(Kelas $kelas, Tugas $tugas)
    {
        abort_unless($kelas->dosen_id === Auth::id(), 403);
        abort_unless($tugas->kelas_id === $kelas->id, 404);
        
        $this->kelas = $kelas;
        $this->tugas = $tugas;
        
        $this->judul = $tugas->judul;
        $this->deskripsi = $tugas->deskripsi;
        $this->tipe = $tugas->tipe_pengumpulan;
        $this->deadline = $tugas->deadline ? $tugas->deadline->format('Y-m-d\TH:i') : null;
        $this->bobotNilai = $tugas->nilai_max;
        $this->maxFileSize = $tugas->maks_ukuran_mb;
        $this->allowedExt = $tugas->format_file ?? [];
        $this->isPublished = $tugas->is_published;
    }

    public function toggleExt(string $ext): void
    {
        if (in_array($ext, $this->allowedExt)) {
            $this->allowedExt = array_values(array_filter($this->allowedExt, fn($e) => $e !== $ext));
        } else {
            $this->allowedExt[] = $ext;
        }
    }

    public function save(): void
    {
        $this->validate([
            'judul'      => 'required|string|max:200',
            'deskripsi'  => 'required|string',
            'deadline'   => 'required|date|after:now',
            'bobotNilai' => 'required|integer|min:1|max:100',
            'fileSoal'   => 'nullable|file|mimes:pdf,doc,docx,zip|max:20480',
        ]);

        if ($this->fileSoal) {
            $soalPath = $this->fileSoal->store("tugas/{$this->kelas->id}", 'public');
        } else {
            $soalPath = $this->tugas->file_soal;
        }

        $this->tugas->update([
            'kelas_id'         => $this->kelas->id,
            'judul'            => $this->judul,
            'deskripsi'        => $this->deskripsi,
            'tipe_pengumpulan' => $this->tipe,
            'deadline'         => $this->deadline,
            'nilai_max'        => $this->bobotNilai,
            'maks_ukuran_mb'   => $this->maxFileSize,
            'format_file'      => $this->allowedExt,
            'file_soal'        => $soalPath,
            'is_published'     => $this->isPublished,
        ]);

        $this->redirect(route('dosen.tugas.detail', ['kelas' => $this->kelas, 'tugas' => $this->tugas->id]));
    }

    public function render()
    {
        return view('livewire.dosen.tugas-edit');
    }
}
