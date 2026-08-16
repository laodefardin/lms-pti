<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.mahasiswa', ['title' => 'NilaiDetail'])]
class NilaiDetail extends Component
{
    public function render()
    {
        return view('livewire.mahasiswa.nilaidetail');
    }
}
