<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Semester;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

#[Layout("components.layouts.dosen", ["title" => "Buka Kelas Baru"])]
class MatakuliahBuat extends Component
{
    use WithFileUploads;

    public $mata_kuliah_id = "";
    public $semester_id    = "";
    public $nama_kelas     = "";
    public $thumbnail      = null;
    public $deskripsi      = "";
    public $hari_kuliah    = "";
    public $jam_mulai      = "";
    public $jam_selesai    = "";
    public $ruangan        = "";
    public $bobot_tugas    = 30;
    public $bobot_kuis     = 20;
    public $bobot_uts      = 25;
    public $bobot_uas      = 25;
    public $bobot_kehadiran = 0;
    public $batas_kehadiran = 75;
    public $mode_materi    = "semua";
    public $status         = "aktif";

    public function mount()
    {
        $sem = Semester::where("is_aktif", true)->first();
        if ($sem) $this->semester_id = $sem->id;
    }

    public function simpan()
    {
        Log::info("simpan() dipanggil", [
            "user_id"       => Auth::id(),
            "mk_id"         => $this->mata_kuliah_id,
            "semester_id"   => $this->semester_id,
            "nama_kelas"    => $this->nama_kelas,
        ]);

        $this->validate([
            'mata_kuliah_id'  => 'required',
            'semester_id'     => 'required',
            'nama_kelas'      => 'required|string|max:30',
            'deskripsi'       => 'nullable|string',
            'hari_kuliah'     => 'nullable|string',
            'jam_mulai'       => 'nullable|string',
            'jam_selesai'     => 'nullable|string',
            'ruangan'         => 'nullable|string|max:50',
            'bobot_tugas'     => 'required|integer|min:0|max:100',
            'bobot_kuis'      => 'required|integer|min:0|max:100',
            'bobot_uts'       => 'required|integer|min:0|max:100',
            'bobot_uas'       => 'required|integer|min:0|max:100',
            'bobot_kehadiran' => 'required|integer|min:0|max:100',
            'batas_kehadiran' => 'required|integer|min:0|max:100',
            'mode_materi'     => 'required|in:semua,bertahap',
            'status'          => 'required|in:aktif,selesai,arsip',
        ], [
            'mata_kuliah_id.required'  => 'Mata kuliah wajib dipilih.',
            'semester_id.required'     => 'Semester wajib dipilih.',
            'nama_kelas.required'      => 'Nama kelas wajib diisi.',
            'nama_kelas.max'           => 'Nama kelas maksimal 30 karakter.',
            'bobot_tugas.required'     => 'Bobot tugas wajib diisi.',
            'bobot_kuis.required'      => 'Bobot kuis wajib diisi.',
            'bobot_uts.required'       => 'Bobot UTS wajib diisi.',
            'bobot_uas.required'       => 'Bobot UAS wajib diisi.',
            'bobot_kehadiran.required' => 'Bobot kehadiran wajib diisi.',
        ]);

        Log::info("simpan() validasi OK");

        $total = (int)$this->bobot_tugas + (int)$this->bobot_kuis
               + (int)$this->bobot_uts  + (int)$this->bobot_uas
               + (int)$this->bobot_kehadiran;

        if ($total !== 100) {
            $this->addError("bobot_tugas", "Total bobot harus 100%. Saat ini: " . $total . "%");
            return;
        }

        $thumbnailPath = null;
        if ($this->thumbnail) {
            $thumbnailPath = $this->thumbnail->store("thumbnails", "public");
        }

        try {
            $kelas = Kelas::create([
                "mata_kuliah_id"  => $this->mata_kuliah_id,
                "dosen_id"        => Auth::id(),
                "semester_id"     => $this->semester_id,
                "nama_kelas"      => $this->nama_kelas,
                "thumbnail"       => $thumbnailPath,
                "deskripsi"       => $this->deskripsi ?: null,
                "hari_kuliah"     => $this->hari_kuliah ?: null,
                "jam_mulai"       => $this->jam_mulai ?: null,
                "jam_selesai"     => $this->jam_selesai ?: null,
                "ruangan"         => $this->ruangan ?: null,
                "bobot_tugas"     => (int)$this->bobot_tugas,
                "bobot_kuis"      => (int)$this->bobot_kuis,
                "bobot_kehadiran" => (int)$this->bobot_kehadiran,
                "bobot_uts"       => (int)$this->bobot_uts,
                "bobot_uas"       => (int)$this->bobot_uas,
                "batas_kehadiran" => (int)$this->batas_kehadiran,
                "mode_materi"     => $this->mode_materi,
                "status"          => $this->status,
            ]);

            Log::info("simpan() kelas dibuat ID=" . $kelas->id);
            session()->flash("success", "Kelas berhasil dibuat!");
            return redirect()->route("dosen.matakuliah.index");

        } catch (\Exception $e) {
            Log::error("simpan() ERROR: " . $e->getMessage());
            $this->addError("bobot_tugas", "Gagal: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view("livewire.dosen.matakuliah-buat", [
            "daftarMk"       => MataKuliah::orderBy("nama")->get(),
            "daftarSemester" => Semester::orderBy("id", "desc")->get(),
        ]);
    }
}
