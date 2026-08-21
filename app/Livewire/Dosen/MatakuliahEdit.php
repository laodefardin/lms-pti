<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.dosen', ['title' => 'Edit Kelas'])]
class MatakuliahEdit extends Component
{
    use WithFileUploads;

    public Kelas $kelas;

    public $mata_kuliah_id;
    public $semester_id;
    public $nama_kelas;
    public $thumbnail;
    public $thumbnail_lama;
    public $deskripsi;
    public $hari_kuliah;
    public $jam_mulai;
    public $jam_selesai;
    public $ruangan;
    public $bobot_tugas;
    public $bobot_kuis;
    public $bobot_uts;
    public $bobot_uas;
    public $bobot_kehadiran;
    public $batas_kehadiran;
    public $mode_materi;
    public $status;

    protected $rules = [
        'mata_kuliah_id' => 'required',
        'semester_id'    => 'required',
        'nama_kelas'     => 'required|string|max:30',
        'thumbnail'      => 'nullable|image|max:2048',
        'deskripsi'      => 'nullable|string',
        'hari_kuliah'    => 'nullable|in:senin,selasa,rabu,kamis,jumat,sabtu',
        'jam_mulai'      => 'nullable|string',
        'jam_selesai'    => 'nullable|string',
        'ruangan'        => 'nullable|string|max:50',
        'bobot_tugas'    => 'required|integer|min:0|max:100',
        'bobot_kuis'     => 'required|integer|min:0|max:100',
        'bobot_uts'      => 'required|integer|min:0|max:100',
        'bobot_uas'      => 'required|integer|min:0|max:100',
        'bobot_kehadiran'=> 'required|integer|min:0|max:100',
        'batas_kehadiran'=> 'required|integer|min:0|max:100',
        'mode_materi'    => 'required|in:semua,bertahap',
        'status'         => 'required|in:aktif,selesai,arsip',
    ];

    protected $messages = [
        'mata_kuliah_id.required' => 'Mata kuliah wajib dipilih.',
        'semester_id.required'    => 'Semester wajib dipilih.',
        'nama_kelas.required'     => 'Nama kelas wajib diisi.',
        'nama_kelas.max'          => 'Nama kelas maksimal 30 karakter.',
        'bobot_tugas.required'    => 'Bobot tugas wajib diisi.',
        'bobot_kuis.required'     => 'Bobot kuis wajib diisi.',
        'bobot_uts.required'      => 'Bobot UTS wajib diisi.',
        'bobot_uas.required'      => 'Bobot UAS wajib diisi.',
        'bobot_kehadiran.required'=> 'Bobot kehadiran wajib diisi.',
        'ruangan.max'             => 'Ruangan maksimal 50 karakter.',
    ];

    public function mount(Kelas $kelas)
    {
        $this->kelas = $kelas;
        
        if ($this->kelas->dosen_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $this->mata_kuliah_id = $kelas->mata_kuliah_id;
        $this->semester_id = $kelas->semester_id;
        $this->nama_kelas = $kelas->nama_kelas;
        $this->thumbnail_lama = $kelas->thumbnail;
        $this->deskripsi = $kelas->deskripsi;
        $this->hari_kuliah = $kelas->hari_kuliah;
        $this->jam_mulai = $kelas->jam_mulai ? substr($kelas->jam_mulai, 0, 5) : null;
        $this->jam_selesai = $kelas->jam_selesai ? substr($kelas->jam_selesai, 0, 5) : null;
        $this->ruangan = $kelas->ruangan;
        $this->bobot_tugas = $kelas->bobot_tugas;
        $this->bobot_kuis = $kelas->bobot_kuis;
        $this->bobot_uts = $kelas->bobot_uts;
        $this->bobot_uas = $kelas->bobot_uas;
        $this->bobot_kehadiran = $kelas->bobot_kehadiran;
        $this->batas_kehadiran = $kelas->batas_kehadiran;
        $this->mode_materi = $kelas->mode_materi;
        $this->status = $kelas->status;
    }

    public function simpan()
    {
        $this->validate();

        $totalWeight = $this->bobot_tugas + $this->bobot_kuis + $this->bobot_uts + $this->bobot_uas + $this->bobot_kehadiran;
        if ($totalWeight !== 100) {
            $this->addError('bobot_tugas', 'Total bobot harus 100%. Saat ini: ' . $totalWeight . '%');
            return;
        }

        $thumbnailPath = $this->thumbnail_lama;
        if ($this->thumbnail) {
            if ($this->thumbnail_lama) {
                Storage::disk('public')->delete($this->thumbnail_lama);
            }
            $thumbnailPath = $this->thumbnail->store('thumbnails', 'public');
        }

        $this->kelas->update([
            'mata_kuliah_id' => $this->mata_kuliah_id,
            'semester_id' => $this->semester_id,
            'nama_kelas' => $this->nama_kelas,
            'thumbnail' => $thumbnailPath,
            'deskripsi' => $this->deskripsi,
            'hari_kuliah' => $this->hari_kuliah,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'ruangan' => $this->ruangan,
            'bobot_tugas' => $this->bobot_tugas,
            'bobot_kuis' => $this->bobot_kuis,
            'bobot_kehadiran' => $this->bobot_kehadiran,
            'bobot_uts' => $this->bobot_uts,
            'bobot_uas' => $this->bobot_uas,
            'batas_kehadiran' => $this->batas_kehadiran,
            'mode_materi' => $this->mode_materi,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Pengaturan kelas berhasil diperbarui.');
        return redirect()->route('dosen.matakuliah.detail', $this->kelas->id);
    }

    public function hapusThumbnail()
    {
        if ($this->kelas->thumbnail) {
            Storage::disk('public')->delete($this->kelas->thumbnail);
            $this->kelas->update(['thumbnail' => null]);
            $this->thumbnail_lama = null;
        }
    }

    public function render()
    {
        return view('livewire.dosen.matakuliah-edit', [
            'daftarMk' => MataKuliah::orderBy('nama')->get(),
            'daftarSemester' => Semester::orderBy('id', 'desc')->get(),
        ]);
    }
}
