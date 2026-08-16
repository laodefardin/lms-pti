<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\{Kelas, Pertemuan, KontenMateri};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.dosen', ['title' => 'Tambah Materi'])]
class MateriBuat extends Component
{
    use WithFileUploads;

    public Kelas $kelas;

    // Pertemuan
    public ?int   $pertemuanId = null;
    public string $topik       = '';
    public ?string $tanggal    = null;
    public bool   $buatPertemuanBaru = false;

    // Konten
    public string $judul          = '';
    public string $tipe           = 'artikel';  // artikel|video|pdf|kode|link
    public string $konten         = '';
    public ?string $url           = null;
    public $filePdf               = null;
    public ?int   $estimasiMenit  = null;
    public int    $urutan         = 1;
    public bool   $isPublished    = false;

    public bool   $saved = false;

    public function mount(Kelas $kelas)
    {
        abort_unless($kelas->dosen_id === Auth::id(), 403);
        $this->kelas   = $kelas;
        $this->urutan  = KontenMateri::whereHas('pertemuan', fn($q) => $q->where('kelas_id', $kelas->id))->max('urutan') + 1;

        // Default ke pertemuan terakhir
        $last = $kelas->pertemuan()->orderByDesc('nomor')->first();
        if ($last) $this->pertemuanId = $last->id;
    }

    public function updatedTipe(): void
    {
        $this->konten = '';
        $this->url    = null;
        $this->filePdf = null;
    }

    public function save(): void
    {
        $this->validate([
            'judul'         => 'required|string|max:200',
            'tipe'          => 'required|in:artikel,video,pdf,kode,link',
            'estimasiMenit' => 'nullable|integer|min:1|max:600',
            'konten'        => $this->tipe === 'artikel' || $this->tipe === 'kode' ? 'required' : 'nullable',
            'url'           => $this->tipe === 'video' || $this->tipe === 'link' ? 'required|url' : 'nullable',
            'filePdf'       => $this->tipe === 'pdf' ? 'required|file|mimes:pdf|max:20480' : 'nullable',
        ], [
            'judul.required'   => 'Judul materi wajib diisi.',
            'url.required'     => 'URL wajib diisi untuk tipe ini.',
            'filePdf.required' => 'File PDF wajib diupload.',
        ]);

        // Buat pertemuan baru jika diperlukan
        if ($this->buatPertemuanBaru) {
            $this->validate(['topik' => 'required|string|max:200']);
            $nomor = $this->kelas->pertemuan()->max('nomor') + 1;
            $pertemuan = Pertemuan::create([
                'kelas_id' => $this->kelas->id,
                'nomor'    => $nomor,
                'topik'    => $this->topik,
                'tanggal'  => $this->tanggal,
            ]);
            $this->pertemuanId = $pertemuan->id;
        }

        $filePath = null;
        if ($this->tipe === 'pdf' && $this->filePdf) {
            $filePath = $this->filePdf->store("materi/{$this->kelas->id}", 'public');
        }

        $ikon = match($this->tipe) {
            'video'   => '🎬',
            'pdf'     => '📄',
            'artikel' => '📝',
            'kode'    => '💻',
            'link'    => '🔗',
            default   => '📁',
        };

        KontenMateri::create([
            'pertemuan_id'   => $this->pertemuanId,
            'judul'          => $this->judul,
            'tipe'           => $this->tipe,
            'konten'         => $this->tipe === 'artikel' || $this->tipe === 'kode' ? $this->konten : null,
            'url'            => $this->tipe === 'video' || $this->tipe === 'link' ? $this->url : null,
            'file_path'      => $filePath,
            'estimasi_menit' => $this->estimasiMenit,
            'urutan'         => $this->urutan,
            'is_published'   => $this->isPublished,
            'ikon'           => $ikon,
        ]);

        $this->saved = true;
        $this->redirect(route('dosen.matakuliah.detail', $this->kelas));
    }

    public function render()
    {
        return view('livewire.dosen.materi-buat', [
            'pertemuanList' => $this->kelas->pertemuan()->orderBy('nomor')->get(),
        ]);
    }
}
