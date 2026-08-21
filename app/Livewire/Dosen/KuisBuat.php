<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{Kelas, Kuis, BankSoal, KuisSoal};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KuisBuat extends Component
{
    public Kelas $kelas;

    // Info kuis
    public string  $judul         = '';
    public string  $deskripsi     = '';
    public string  $tipe          = 'kuis';  // kuis|uts|uas
    public int     $durasiMenit   = 60;
    public ?string $bukaAt        = null;
    public ?string $tutupAt       = null;
    public int     $maksPercobaan = 1;
    public bool    $acakSoal      = false;
    public bool    $tampilkanPembahasan = true;
    public bool    $isPublished   = false;
    public ?int    $pertemuanId   = null;

    // Bank soal (sementara di memori sebelum simpan)
    public array $soalList = [];   // [{pertanyaan, tipe, bobot, pilihan:[{teks,is_benar}], jawaban}]
    public int   $editSoalIdx = -1; // -1 = not editing

    // Soal form state
    public string $soalPertanyaan = '';
    public string $soalTipe       = 'pilihan_ganda'; // pilihan_ganda|essay
    public int    $soalBobot      = 1;
    public array  $soalPilihan    = [
        ['teks' => '', 'is_benar' => false],
        ['teks' => '', 'is_benar' => false],
        ['teks' => '', 'is_benar' => false],
        ['teks' => '', 'is_benar' => false],
    ];

    public bool $showSoalForm = false;

    public function mount(Kelas $kelas)
    {
        abort_unless($kelas->dosen_id === Auth::id(), 403);
        $this->kelas   = $kelas;
        $this->bukaAt  = now()->format('Y-m-d\TH:i');
        $this->tutupAt = now()->addDays(3)->format('Y-m-d\TH:i');
        $this->pertemuanId = request('pertemuan_id') ? (int) request('pertemuan_id') : null;
    }

    public function addSoal(): void
    {
        $this->showSoalForm = true;
        $this->editSoalIdx  = -1;
        $this->resetSoalForm();
    }

    public function editSoal(int $idx): void
    {
        $s = $this->soalList[$idx];
        $this->editSoalIdx    = $idx;
        $this->soalPertanyaan = $s['pertanyaan'];
        $this->soalTipe       = $s['tipe'];
        $this->soalBobot      = $s['bobot'];
        $this->soalPilihan    = $s['pilihan'] ?? $this->defaultPilihan();
        $this->showSoalForm   = true;
    }

    public function hapusSoal(int $idx): void
    {
        array_splice($this->soalList, $idx, 1);
        $this->soalList = array_values($this->soalList);
    }

    public function setPilihBenar(int $idx): void
    {
        foreach ($this->soalPilihan as $i => $p) {
            $this->soalPilihan[$i]['is_benar'] = ($i === $idx);
        }
    }

    public function addPilihan(): void
    {
        if (count($this->soalPilihan) < 6) {
            $this->soalPilihan[] = ['teks' => '', 'is_benar' => false];
        }
    }

    public function hapusPilihan(int $idx): void
    {
        if (count($this->soalPilihan) > 2) {
            array_splice($this->soalPilihan, $idx, 1);
            $this->soalPilihan = array_values($this->soalPilihan);
        }
    }

    public function simpanSoal(): void
    {
        $this->validate([
            'soalPertanyaan'   => 'required|string',
            'soalTipe'         => 'required|in:pilihan_ganda,essay',
            'soalBobot'        => 'required|integer|min:1',
        ]);

        $jawaban = '';
        if ($this->soalTipe === 'pilihan_ganda') {
            $hasBenar = false;
            foreach ($this->soalPilihan as $p) {
                if ($p['is_benar']) {
                    $jawaban = $p['teks'];
                    $hasBenar = true;
                }
            }
            if (!$hasBenar) {
                $this->addError('soalPilihan', 'Pilih salah satu jawaban yang benar.');
                return;
            }
        }

        $soalData = [
            'pertanyaan' => $this->soalPertanyaan,
            'tipe'       => $this->soalTipe,
            'bobot'      => $this->soalBobot,
            'pilihan'    => $this->soalTipe === 'pilihan_ganda' ? array_values($this->soalPilihan) : [],
            'jawaban'    => $jawaban,
        ];

        if ($this->editSoalIdx >= 0) {
            $this->soalList[$this->editSoalIdx] = $soalData;
        } else {
            $this->soalList[] = $soalData;
        }

        $this->showSoalForm = false;
        $this->editSoalIdx  = -1;
        $this->resetSoalForm();
    }

    public function batalSoal(): void
    {
        $this->showSoalForm = false;
        $this->editSoalIdx  = -1;
        $this->resetSoalForm();
    }

    private function resetSoalForm(): void
    {
        $this->soalPertanyaan = '';
        $this->soalTipe       = 'pilihan_ganda';
        $this->soalBobot      = 1;
        $this->soalPilihan    = $this->defaultPilihan();
    }

    private function defaultPilihan(): array
    {
        return [
            ['teks' => '', 'is_benar' => false],
            ['teks' => '', 'is_benar' => false],
            ['teks' => '', 'is_benar' => false],
            ['teks' => '', 'is_benar' => false],
        ];
    }

    public function save(): void
    {
        $this->validate([
            'judul'         => 'required|string|max:200',
            'durasiMenit'   => 'required|integer|min:1|max:300',
            'bukaAt'        => 'required|date',
            'tutupAt'       => 'required|date|after:bukaAt',
            'maksPercobaan' => 'required|integer|min:1',
        ]);

        if (empty($this->soalList)) {
            $this->addError('soalList', 'Tambahkan minimal 1 soal.');
            return;
        }

        DB::transaction(function () {
            // Hitung nilai_max = sum(bobot)
            $nilaiMax = collect($this->soalList)->sum('bobot');

            $kuis = Kuis::create([
                'kelas_id'             => $this->kelas->id,
                'pertemuan_id'         => $this->pertemuanId,
                'judul'                => $this->judul,
                'deskripsi'            => $this->deskripsi,
                'tipe'                 => $this->tipe,
                'durasi_menit'         => $this->durasiMenit,
                'buka_at'              => $this->bukaAt,
                'tutup_at'             => $this->tutupAt,
                'maks_percobaan'       => $this->maksPercobaan,
                'acak_soal'            => $this->acakSoal,
                'tampilkan_pembahasan' => $this->tampilkanPembahasan,
                'is_published'         => $this->isPublished,
                'nilai_max'            => min($nilaiMax, 100), // Cap at 100 or scale it later
            ]);

            foreach ($this->soalList as $i => $soalData) {
                // Konversi array pilihan jadi array string (untuk JSON opsi BankSoal)
                $opsiStr = [];
                if ($soalData['tipe'] === 'pilihan_ganda') {
                    foreach ($soalData['pilihan'] as $p) {
                        if (!empty($p['teks'])) {
                            $opsiStr[] = $p['teks'];
                        }
                    }
                }

                $bankSoal = BankSoal::create([
                    'kelas_id'   => $this->kelas->id,
                    'dosen_id'   => Auth::id(),
                    'tipe'       => $soalData['tipe'],
                    'pertanyaan' => $soalData['pertanyaan'],
                    'bobot'      => $soalData['bobot'],
                    'opsi'       => empty($opsiStr) ? null : $opsiStr,
                    'jawaban'    => empty($soalData['jawaban']) ? [] : [$soalData['jawaban']],
                ]);

                KuisSoal::create([
                    'kuis_id'      => $kuis->id,
                    'bank_soal_id' => $bankSoal->id,
                    'urutan'       => $i + 1,
                ]);
            }
        });

        $this->redirect(route('dosen.kuis.index', $this->kelas));
    }

    #[Layout('components.layouts.dosen', ['title' => 'Buat Kuis Baru'])]
    public function render()
    {
        return view('livewire.dosen.kuis-buat');
    }
}
