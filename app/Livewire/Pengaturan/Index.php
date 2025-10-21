<?php

namespace App\Livewire\Pengaturan;

use Livewire\Component;
use Illuminate\View\View;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.pengaturan.index');
    }
}
