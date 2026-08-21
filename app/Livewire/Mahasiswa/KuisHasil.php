<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\KuisSesi;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.mahasiswa', ['title' => 'Hasil Kuis'])]
class KuisHasil extends Component
{
    public KuisSesi $sesi;

    public function mount(KuisSesi $sesi)
    {
        $this->sesi = $sesi->load([
            'kuis', 
            'jawaban.soal.bankSoal',
        ]);

        if ($this->sesi->mahasiswa_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        if ($this->sesi->status !== 'selesai' && $this->sesi->status !== 'timeout') {
            return redirect()->route('mahasiswa.kuis.index')->with('error', 'Kuis belum selesai dikerjakan.');
        }
    }

    public function render()
    {
        $kuis = $this->sesi->kuis;
        $tampilkanPembahasan = $kuis->tampilkan_pembahasan;

        // Calculate time taken
        $waktuMulai = $this->sesi->mulai_at;
        $waktuSelesai = $this->sesi->selesai_at ?? $this->sesi->updated_at;
        $durasiMenit = $waktuMulai ? $waktuMulai->diffInMinutes($waktuSelesai) : 0;
        $durasiDetik = $waktuMulai ? $waktuMulai->diffInSeconds($waktuSelesai) % 60 : 0;
        $durasiText = $durasiMenit . ' menit ' . $durasiDetik . ' detik';

        // Hitung benar/salah
        $benar = 0;
        $salah = 0;
        $totalPG = 0;

        foreach ($this->sesi->jawaban as $jawaban) {
            $soal = $jawaban->soal->bankSoal ?? null;
            if ($soal && $soal->tipe === 'pg') {
                $totalPG++;
                if ($jawaban->jawaban_text === $soal->jawaban) {
                    $benar++;
                } else {
                    $salah++;
                }
            }
        }

        return view('livewire.mahasiswa.kuis-hasil', [
            'kuis' => $kuis,
            'tampilkanPembahasan' => $tampilkanPembahasan,
            'durasiText' => $durasiText,
            'benar' => $benar,
            'salah' => $salah,
            'totalPG' => $totalPG,
        ]);
    }
}
