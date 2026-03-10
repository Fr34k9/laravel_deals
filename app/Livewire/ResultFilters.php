<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Platform;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ResultFilters extends Component
{
    /**
     * Dispatch the platform filter event.
     */
    public function filterByPlatform(int $platformId): void
    {
        $this->dispatch('filterByPlatform', $platformId);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.result-filters', [
            'platforms' => Platform::active()->get(),
        ]);
    }
}
