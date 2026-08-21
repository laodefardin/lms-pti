<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\{Kelas, KontenMateri};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.dosen', ['title' => 'Edit Konten'])]
class KontenEdit extends Component
{
    use WithFileUploads;

    public Kelas $kelas;
    public KontenMateri $kontenModel;

    // Konten fields
    public string  $judul         = '';
    public string  $tipe          = 'artikel';
    public string  $konten        = '';
    public ?string $url           = null;
    public         $filePdf       = null;
    public ?int    $estimasiMenit = null;
    public int     $urutan        = 1;
    public bool    $isPublished   = false;

    public function mount(Kelas $kelas, KontenMateri $konten)
    {
        abort_unless($kelas->dosen_id === Auth::id(), 403);
        abort_unless($konten->pertemuan->kelas_id === $kelas->id, 403);

        $this->kelas       = $kelas;
        $this->kontenModel = $konten;

        // Populate fields from existing record
        $this->judul         = $konten->judul;
        $this->tipe          = $konten->tipe;
        $this->konten        = $konten->konten ?? '';
        $this->url           = $konten->url;
        $this->estimasiMenit = $konten->estimasi_menit > 0 ? $konten->estimasi_menit : null;
        $this->urutan        = $konten->urutan;
        $this->isPublished   = (bool) $konten->is_published;
    }

    public function setTipe($newTipe): void
    {
        $this->tipe   = $newTipe;
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
            'filePdf'       => $this->tipe === 'pdf' && $this->filePdf ? 'file|mimes:pdf|max:20480' : 'nullable',
        ], [
            'judul.required'   => 'Judul materi wajib diisi.',
            'url.required'     => 'URL wajib diisi untuk tipe ini.',
            'konten.required'  => 'Isi konten wajib diisi.',
        ]);

        $filePath = $this->kontenModel->file_path;
        if ($this->tipe === 'pdf' && $this->filePdf) {
            // Delete old file
            if ($filePath) Storage::disk('public')->delete($filePath);
            $filePath = $this->filePdf->store("materi/{$this->kelas->id}", 'public');
        }

        $this->kontenModel->update([
            'judul'          => $this->judul,
            'tipe'           => $this->tipe,
            'konten'         => $this->tipe === 'artikel' || $this->tipe === 'kode' ? $this->konten : null,
            'url'            => $this->tipe === 'video' || $this->tipe === 'link' ? $this->url : null,
            'file_path'      => $filePath,
            'estimasi_menit' => $this->estimasiMenit ?: 0,
            'urutan'         => $this->urutan,
            'is_published'   => $this->isPublished,
        ]);

        session()->flash('success', 'Konten materi berhasil diperbarui!');
        $this->redirect(route('dosen.matakuliah.detail', $this->kelas), navigate: true);
    }

    public function render()
    {
        return view('livewire.dosen.konten-edit');
    }
}
