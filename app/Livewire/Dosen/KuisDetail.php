<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\{Kelas, Kuis, KuisSoal, BankSoal};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KuisDetail extends Component
{
    public Kelas $kelas;
    public Kuis  $kuis;

    // Edit info kuis
    public bool   $editInfo    = false;
    public string $judul       = '';
    public string $deskripsi   = '';
    public int    $durasiMenit = 60;
    public ?string $bukaAt     = null;
    public ?string $tutupAt    = null;
    public int    $maksPercobaan = 1;
    public bool   $acakSoal    = false;
    public bool   $tampilkanPembahasan = true;

    // Soal form
    public bool   $showSoalForm    = false;
    public int    $editSoalId      = -1; // bank_soal id being edited
    public string $soalPertanyaan  = '';
    public string $soalTipe        = 'pilihan_ganda';
    public int    $soalBobot       = 1;
    public array  $soalPilihan     = [
        ['teks' => '', 'is_benar' => false],
        ['teks' => '', 'is_benar' => false],
        ['teks' => '', 'is_benar' => false],
        ['teks' => '', 'is_benar' => false],
    ];

    public function mount(Kelas $kelas, Kuis $kuis)
    {
        if ($kelas->dosen_id !== Auth::id() || $kuis->kelas_id !== $kelas->id) {
            abort(403);
        }
        $this->kelas = $kelas;
        $this->kuis  = $kuis->load(['soal.bankSoal', 'sesi.mahasiswa']);
        $this->fillInfoForm();
    }

    // ─── Info Kuis ───────────────────────────────────────────────

    private function fillInfoForm(): void
    {
        $this->judul              = $this->kuis->judul;
        $this->deskripsi          = $this->kuis->deskripsi ?? '';
        $this->durasiMenit        = $this->kuis->durasi_menit;
        $this->bukaAt             = $this->kuis->buka_at?->format('Y-m-d\TH:i');
        $this->tutupAt            = $this->kuis->tutup_at?->format('Y-m-d\TH:i');
        $this->maksPercobaan      = $this->kuis->maks_percobaan;
        $this->acakSoal           = $this->kuis->acak_soal;
        $this->tampilkanPembahasan= $this->kuis->tampilkan_pembahasan;
    }

    public function saveInfo(): void
    {
        $this->validate([
            'judul'       => 'required|string|max:200',
            'durasiMenit' => 'required|integer|min:1|max:300',
            'bukaAt'      => 'required|date',
            'tutupAt'     => 'required|date|after:bukaAt',
        ]);

        $this->kuis->update([
            'judul'               => $this->judul,
            'deskripsi'           => $this->deskripsi,
            'durasi_menit'        => $this->durasiMenit,
            'buka_at'             => $this->bukaAt,
            'tutup_at'            => $this->tutupAt,
            'maks_percobaan'      => $this->maksPercobaan,
            'acak_soal'           => $this->acakSoal,
            'tampilkan_pembahasan'=> $this->tampilkanPembahasan,
        ]);

        $this->kuis->refresh();
        $this->editInfo = false;
        session()->flash('success', 'Informasi kuis berhasil diperbarui.');
    }

    public function togglePublish(): void
    {
        $this->kuis->update(['is_published' => !$this->kuis->is_published]);
        $this->kuis->refresh();
    }

    // ─── Soal ───────────────────────────────────────────────────

    public function addSoal(): void
    {
        $this->editSoalId     = -1;
        $this->soalPertanyaan = '';
        $this->soalTipe       = 'pilihan_ganda';
        $this->soalBobot      = 1;
        $this->soalPilihan    = $this->defaultPilihan();
        $this->showSoalForm   = true;
    }

    public function editSoal(int $kuisSoalId): void
    {
        $kuisSoal = KuisSoal::with('bankSoal')->find($kuisSoalId);
        if (!$kuisSoal) return;

        $bank = $kuisSoal->bankSoal;
        $this->editSoalId     = $kuisSoalId;
        $this->soalPertanyaan = $bank->pertanyaan;
        $this->soalTipe       = $bank->tipe;
        $this->soalBobot      = $bank->bobot;

        $opsi = $bank->opsi ?? [];
        $jawabanTemp = $bank->jawaban ?? [];
        $this->soalPilihan = [];
        foreach ($opsi as $teks) {
            $isBenar = false;
            $idx = array_search($teks, $jawabanTemp);
            if ($idx !== false) {
                $isBenar = true;
                unset($jawabanTemp[$idx]);
            }
            $this->soalPilihan[] = [
                'teks'     => $teks,
                'is_benar' => $isBenar,
            ];
        }
        if (empty($this->soalPilihan)) {
            $this->soalPilihan = $this->defaultPilihan();
        }

        $this->showSoalForm = true;
    }

    public function hapusSoal(int $kuisSoalId): void
    {
        $kuisSoal = KuisSoal::with('bankSoal')->find($kuisSoalId);
        if ($kuisSoal && $kuisSoal->kuis_id === $this->kuis->id) {
            $kuisSoal->bankSoal?->delete();
            $kuisSoal->delete();
            $this->kuis->load(['soal.bankSoal', 'sesi.mahasiswa']);
            session()->flash('success', 'Soal berhasil dihapus.');
        }
    }

    public function setPilihBenar(int $idx): void
    {
        foreach ($this->soalPilihan as $i => $_) {
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
            'soalPertanyaan' => 'required|string',
            'soalTipe'       => 'required|in:pilihan_ganda,essay',
            'soalBobot'      => 'required|integer|min:1',
        ]);

        $opsiStr = [];
        $jawaban = [];
        if ($this->soalTipe === 'pilihan_ganda') {
            $hasBenar = false;
            foreach ($this->soalPilihan as $p) {
                if (!empty($p['teks'])) {
                    $opsiStr[] = $p['teks'];
                    if ($p['is_benar']) { $jawaban[] = $p['teks']; $hasBenar = true; }
                }
            }
            if (!$hasBenar) {
                $this->addError('soalPilihan', 'Pilih salah satu jawaban yang benar.');
                return;
            }
        }

        DB::transaction(function () use ($opsiStr, $jawaban) {
            if ($this->editSoalId > 0) {
                // Update existing
                $kuisSoal = KuisSoal::find($this->editSoalId);
                if ($kuisSoal && $kuisSoal->kuis_id === $this->kuis->id) {
                    $kuisSoal->bankSoal->update([
                        'pertanyaan' => $this->soalPertanyaan,
                        'tipe'       => $this->soalTipe,
                        'bobot'      => $this->soalBobot,
                        'opsi'       => empty($opsiStr) ? null : $opsiStr,
                        'jawaban'    => $jawaban,
                    ]);
                }
            } else {
                // Create new
                $urutan   = $this->kuis->soal->count() + 1;
                $bankSoal = BankSoal::create([
                    'kelas_id'   => $this->kelas->id,
                    'dosen_id'   => Auth::id(),
                    'tipe'       => $this->soalTipe,
                    'pertanyaan' => $this->soalPertanyaan,
                    'bobot'      => $this->soalBobot,
                    'opsi'       => empty($opsiStr) ? null : $opsiStr,
                    'jawaban'    => $jawaban,
                ]);
                KuisSoal::create([
                    'kuis_id'      => $this->kuis->id,
                    'bank_soal_id' => $bankSoal->id,
                    'urutan'       => $urutan,
                ]);
            }
        });

        $this->kuis->load(['soal.bankSoal', 'sesi.mahasiswa']);
        $this->showSoalForm = false;
        $this->editSoalId   = -1;
    }

    public function batalSoal(): void
    {
        $this->showSoalForm = false;
        $this->editSoalId   = -1;
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

    #[Layout('components.layouts.dosen', ['title' => 'Detail Kuis'])]
    public function render()
    {
        return view('livewire.dosen.kuis-detail');
    }
}
