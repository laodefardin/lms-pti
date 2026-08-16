<?php

namespace App\Livewire\Dosen;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.dosen', ['title' => 'Profil'])]
class Profil extends Component
{
    public function render()
    {
        return view('livewire.dosen.profil');
    }
}
