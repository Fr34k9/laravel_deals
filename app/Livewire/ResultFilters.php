<?php

namespace App\Livewire;

use App\Models\Platform;
use Livewire\Component;

class ResultFilters extends Component
{
    public function filterByPlatform(Int $platformId)
    {
        $this->dispatch('filterByPlatform', $platformId);
    }

    public function render()
    {
        $platforms = Platform::all();

        return view('livewire.result-filters', compact('platforms'));
    }
}
