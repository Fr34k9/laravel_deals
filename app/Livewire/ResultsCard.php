<?php

namespace App\Livewire;

use Livewire\Component;

class ResultsCard extends Component
{
    public $deal;
    public $blur;

    public function mount($deal)
    {
        $this->deal = $deal;
        $this->blur = $deal->invalid;
    }

    public function toggleDealVisibility()
    {
        $this->deal->update([
            'invalid' => !$this->deal->invalid
        ]);
        $this->blur = $this->deal->invalid;
    }

    public function render()
    {
        return view('livewire.results-card');
    }
}
