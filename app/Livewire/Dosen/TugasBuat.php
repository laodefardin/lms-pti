<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\{Kelas, Tugas};
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.dosen', ['title' => 'Buat Tugas'])]
class TugasBuat extends Component
{
    use WithFileUploads;

    public Kelas $kelas;

    public string  $judul       = '';
    public string  $deskripsi   = '';
    public string  $tipe        = 'file';  // file|link|file_link
    public ?string $deadline    = null;
    public int     $bobotNilai  = 100;
    public int     $maxFileSize = 10;        // MB
    public array   $allowedExt  = ['pdf', 'docx', 'zip'];
    public bool    $isPublished = false;
    public $fileSoal            = null;
    public ?int    $pertemuanId = null;

    public function mount(Kelas $kelas)
    {
        abort_unless($kelas->dosen_id === Auth::id(), 403);
        $this->kelas = $kelas;
        $this->deadline = now()->addDays(7)->format('Y-m-d\TH:i');
        $this->pertemuanId = request('pertemuan_id') ? (int) request('pertemuan_id') : null;
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

        $soalPath = null;
        if ($this->fileSoal) {
            $soalPath = $this->fileSoal->store("tugas/{$this->kelas->id}", 'public');
        }

        Tugas::create([
            'kelas_id'         => $this->kelas->id,
            'pertemuan_id'     => $this->pertemuanId,
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

        $this->redirect(route('dosen.tugas.index', $this->kelas));
    }

    public function render()
    {
        return view('livewire.dosen.tugas-buat');
    }
}
