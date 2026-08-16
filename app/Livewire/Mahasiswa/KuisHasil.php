<?php

namespace App\Livewire\Mahasiswa;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.mahasiswa', ['title' => 'KuisHasil'])]
class KuisHasil extends Component
{
    public function render()
    {
        return view('livewire.mahasiswa.kuishasil');
    }
}
