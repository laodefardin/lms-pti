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
        
        $reqPertemuanId = request()->query('pertemuan');
        
        if ($reqPertemuanId && $this->kelas->pertemuan->contains('id', $reqPertemuanId)) {
            $this->selectPertemuan($reqPertemuanId);
        } elseif ($kelas->pertemuan->count() > 0) {
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
        if (!$this->selectedPertemuanId) return;

        $absensi = \App\Models\Absensi::firstOrCreate(
            ['pertemuan_id' => $this->selectedPertemuanId],
            [
                'kelas_id' => $this->kelas->id,
                'token' => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(6)),
                'is_aktif' => true
            ]
        );

        $gamifikasi = app(\App\Services\GamifikasiService::class);
        $nilaiService = app(\App\Services\NilaiService::class);

        foreach ($this->absensiData as $mhsId => $data) {
            if (!empty($data['status'])) {
                AbsensiMahasiswa::updateOrCreate(
                    ['absensi_id' => $absensi->id, 'mahasiswa_id' => $mhsId],
                    ['status' => $data['status'], 'keterangan' => $data['keterangan'] ?? null]
                );

                if ($data['status'] === 'hadir') {
                    $gamifikasi->berikanPoin(
                        userId: $mhsId,
                        tipeAktivitas: \App\Models\GamifikasiPoin::ABSENSI_HADIR,
                        kelasId: $this->kelas->id,
                        keterangan: "Hadir pada pertemuan: {$this->kelas->pertemuan->firstWhere('id', $this->selectedPertemuanId)?->topik}",
                        referenceId: $absensi->id,
                        allowDuplicate: false
                    );
                }

                // Update nilai akhir karena komponen kehadiran berubah
                dispatch(function() use ($nilaiService, $mhsId) {
                    $nilaiService->hitungNilaiAkhir($mhsId, $this->kelas->id);
                })->afterResponse();
            }
        }

        session()->flash('success', 'Data absensi berhasil disimpan dan poin kehadiran diperbarui.');
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
