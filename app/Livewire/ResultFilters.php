<?php

namespace App\Livewire;

use App\Models\Platform;
use Livewire\Component;

class ResultFilters extends Component
{
    public function filterByPlatform(int $platformId)
    {
        $this->dispatch('filterByPlatform', $platformId);
    }

    public function render()
    {
        $platforms = Platform::where('active', true)->get();

        return view('livewire.result-filters', compact('platforms'));
    }
}
