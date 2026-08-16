<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{Kelas, Kuis, KuisSoal, KuisPilihan};
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.dosen', ['title' => 'Buat Kuis'])]
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
    public int     $maxPercobaan  = 1;
    public bool    $acakSoal      = false;
    public bool    $tampilkanNilai= true;
    public ?int    $passingGrade  = 60;
    public bool    $isPublished   = false;

    // Bank soal
    public array $soalList = [];   // [{pertanyaan, tipe, bobot, pilihan:[{teks,is_benar}]}]
    public int   $editSoalIdx = -1; // -1 = not editing

    // Soal form
    public string $soalPertanyaan = '';
    public string $soalTipe       = 'pg'; // pg|esai|benar_salah
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
            'soalTipe'         => 'required|in:pg,esai,benar_salah',
            'soalBobot'        => 'required|integer|min:1',
        ]);

        $soalData = [
            'pertanyaan' => $this->soalPertanyaan,
            'tipe'       => $this->soalTipe,
            'bobot'      => $this->soalBobot,
            'pilihan'    => $this->soalTipe !== 'esai' ? array_values($this->soalPilihan) : [],
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
        $this->soalTipe       = 'pg';
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
            'judul'      => 'required|string|max:200',
            'durasiMenit'=> 'required|integer|min:1|max:300',
            'bukaAt'     => 'required|date',
            'tutupAt'    => 'required|date|after:bukaAt',
        ]);

        if (empty($this->soalList)) {
            $this->addError('soalList', 'Tambahkan minimal 1 soal.');
            return;
        }

        $kuis = Kuis::create([
            'kelas_id'        => $this->kelas->id,
            'judul'           => $this->judul,
            'deskripsi'       => $this->deskripsi,
            'tipe'            => $this->tipe,
            'durasi_menit'    => $this->durasiMenit,
            'buka_at'         => $this->bukaAt,
            'tutup_at'        => $this->tutupAt,
            'max_percobaan'   => $this->maxPercobaan,
            'acak_soal'       => $this->acakSoal,
            'tampilkan_nilai' => $this->tampilkanNilai,
            'passing_grade'   => $this->passingGrade,
            'is_published'    => $this->isPublished,
        ]);

        foreach ($this->soalList as $i => $soalData) {
            $soal = KuisSoal::create([
                'kuis_id'    => $kuis->id,
                'pertanyaan' => $soalData['pertanyaan'],
                'tipe'       => $soalData['tipe'],
                'bobot'      => $soalData['bobot'],
                'urutan'     => $i + 1,
            ]);

            foreach ($soalData['pilihan'] ?? [] as $j => $p) {
                KuisPilihan::create([
                    'soal_id'  => $soal->id,
                    'teks'     => $p['teks'],
                    'is_benar' => $p['is_benar'],
                    'urutan'   => $j + 1,
                ]);
            }
        }

        $this->redirect(route('dosen.matakuliah.detail', $this->kelas));
    }

    public function render()
    {
        return view('livewire.dosen.kuis-buat');
    }
}
