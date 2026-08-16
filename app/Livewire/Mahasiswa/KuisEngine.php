<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{Kuis, KuisSesi, KuisSoal, KuisJawaban};
use App\Services\{GamifikasiService, NilaiService};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

// Kuis Engine tidak pakai sidebar layout — fullscreen
class KuisEngine extends Component
{
    public Kuis $kuis;
    public KuisSesi $sesi;

    public int  $currentIndex = 0;
    public array $jawaban = [];       // soal_id => pilihan_id / jawaban_text
    public bool  $selesai  = false;
    public int   $sisaDetik;
    public bool  $confirmSubmit = false;

    public function mount(Kuis $kuis)
    {
        abort_unless(
            $kuis->kelas->mahasiswa()->where('mahasiswa_id', Auth::id())->exists(),
            403
        );
        abort_unless($kuis->is_published && now() >= $kuis->buka_at && now() <= $kuis->tutup_at, 403, 'Kuis tidak tersedia saat ini.');

        $this->kuis = $kuis;

        // Buat atau lanjutkan sesi
        $this->sesi = KuisSesi::firstOrCreate(
            ['kuis_id' => $kuis->id, 'mahasiswa_id' => Auth::id(), 'status' => 'berlangsung'],
            ['mulai_at' => now(), 'status' => 'berlangsung']
        );

        if ($this->sesi->status === 'selesai') {
            $this->selesai = true;
            return;
        }

        // Hitung sisa waktu
        $elapsed = now()->diffInSeconds($this->sesi->mulai_at);
        $this->sisaDetik = max(0, ($kuis->durasi_menit * 60) - $elapsed);

        if ($this->sisaDetik === 0) {
            $this->submitKuis();
            return;
        }

        // Load jawaban existing
        $existing = KuisJawaban::where('sesi_id', $this->sesi->id)->get();
        foreach ($existing as $j) {
            $this->jawaban[$j->soal_id] = $j->pilihan_id ?? $j->jawaban_text;
        }
    }

    public function pilih(int $soalId, $nilai): void
    {
        $this->jawaban[$soalId] = $nilai;
        // Auto-save jawaban
        $soal = KuisSoal::find($soalId);
        KuisJawaban::updateOrCreate(
            ['sesi_id' => $this->sesi->id, 'soal_id' => $soalId],
            [
                'pilihan_id'   => is_int($nilai) ? $nilai : null,
                'jawaban_text' => is_string($nilai) ? $nilai : null,
            ]
        );
    }

    public function goTo(int $index): void
    {
        $this->currentIndex = $index;
    }

    public function submitKuis(): void
    {
        $soalList = $this->getSoal();
        $total    = $soalList->count();
        $benar    = 0;

        foreach ($soalList as $soal) {
            $jawaban = $this->jawaban[$soal->id] ?? null;
            if ($soal->tipe === 'pg' || $soal->tipe === 'benar_salah') {
                $correct = $soal->pilihan()->where('is_benar', true)->first();
                if ($correct && $jawaban == $correct->id) {
                    $benar += $soal->bobot ?? 1;
                }
            }
        }

        $nilaiAkhir = $total > 0 ? round($benar / $total * 100) : 0;

        $this->sesi->update([
            'status'     => 'selesai',
            'selesai_at' => now(),
            'nilai'      => $nilaiAkhir,
        ]);

        $gamifikasi = app(GamifikasiService::class);
        $gamifikasi->berikanPoin(
            userId: Auth::id(),
            tipeAktivitas: \App\Models\GamifikasiPoin::KUIS_SELESAI,
            kelasId: $this->kuis->kelas_id,
            keterangan: "Menyelesaikan kuis: {$this->kuis->judul}",
            referenceId: $this->kuis->id,
            allowDuplicate: false
        );
        
        if ($nilaiAkhir >= 70) {
            $gamifikasi->berikanPoin(
                userId: Auth::id(),
                tipeAktivitas: \App\Models\GamifikasiPoin::KUIS_LULUS,
                kelasId: $this->kuis->kelas_id,
                keterangan: "Lulus kuis: {$this->kuis->judul} dengan nilai {$nilaiAkhir}",
                referenceId: $this->kuis->id,
                allowDuplicate: false
            );
        }

        dispatch(function() {
            app(NilaiService::class)->hitungNilaiAkhir(Auth::id(), $this->kuis->kelas_id);
        })->afterResponse();

        $this->selesai      = true;
        $this->confirmSubmit = false;
    }

    public function getSoal(): Collection
    {
        $q = KuisSoal::where('kuis_id', $this->kuis->id)->with('pilihan');
        if ($this->kuis->acak_soal) $q->inRandomOrder();
        else $q->orderBy('urutan');
        return $q->get();
    }

    public function render()
    {
        $soalList = $this->selesai ? collect() : $this->getSoal();
        return view('livewire.mahasiswa.kuis-engine', [
            'soalList' => $soalList,
        ])->layout('components.layouts.kuis');
    }
}
