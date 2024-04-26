<?php

namespace App\Livewire;

use Livewire\Component;

class ResultsCard extends Component
{
    public $deal;

    public function render()
    {
        return view('livewire.results-card');
    }
}
