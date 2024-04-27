<?php

namespace App\Livewire;

use App\Models\Platform;
use Livewire\Attributes\On;
use Livewire\Component;

class Modal extends Component
{
    public $isOpen = false;

    #[On('openModal')]
    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        $platforms = Platform::where('active', true)->get();

        return view('livewire.modal', compact('platforms'));
    }
}
