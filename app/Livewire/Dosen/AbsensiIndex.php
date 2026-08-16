<?php

namespace App\Livewire\Dosen;

use App\Models\Kelas;
use App\Models\Pertemuan;
use App\Models\AbsensiMahasiswa;
use Livewire\Component;
use Livewire\Attributes\Layout;

class AbsensiIndex extends Component
{
    public Kelas $kelas;
    public $selectedPertemuanId = null;
    public $absensiData = [];

    public function mount(Kelas $kelas)
    {
        if ($kelas->dosen_id !== auth()->id()) {
            abort(403);
        }
        $this->kelas = $kelas->load(['pertemuan', 'mahasiswa']);
        
        if ($kelas->pertemuan->count() > 0) {
            $this->selectPertemuan($kelas->pertemuan->first()->id);
        }
    }

    public function selectPertemuan($id)
    {
        $this->selectedPertemuanId = $id;
        $this->loadAbsensiData();
    }

    public function loadAbsensiData()
    {
        $this->absensiData = [];
        if (!$this->selectedPertemuanId) return;

        // Fetch from AbsensiMahasiswa using selected pertemuan
        $existingRecords = AbsensiMahasiswa::whereHas('absensi', function($q) {
            $q->where('pertemuan_id', $this->selectedPertemuanId);
        })->get()->keyBy('mahasiswa_id');

        foreach ($this->kelas->mahasiswa as $mhs) {
            if ($existingRecords->has($mhs->id)) {
                $this->absensiData[$mhs->id] = [
                    'status' => $existingRecords[$mhs->id]->status,
                    'keterangan' => $existingRecords[$mhs->id]->keterangan ?? ''
                ];
            } else {
                $this->absensiData[$mhs->id] = [
                    'status' => '',
                    'keterangan' => ''
                ];
            }
        }
    }

    public function autoFillHadir()
    {
        foreach ($this->absensiData as $mhsId => $data) {
            if (empty($data['status'])) {
                $this->absensiData[$mhsId]['status'] = 'hadir';
            }
        }
    }

    public function saveAbsensi()
    {
        // Add save logic here based on your database structure.
        session()->flash('success', 'Data absensi berhasil disimpan.');
    }

    #[Layout('components.layouts.dosen', ['title' => 'Manajemen Absensi'])]
    public function render()
    {
        $selectedPertemuan = $this->kelas->pertemuan->firstWhere('id', $this->selectedPertemuanId);
        
        return view('livewire.dosen.absensi-index', [
            'pertemuans' => $this->kelas->pertemuan,
            'selectedPertemuan' => $selectedPertemuan,
        ]);
    }
}
