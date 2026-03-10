<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ResultList extends Component
{
    /**
     * The ID of the currently selected platform for filtering.
     *
     * @var int|null
     */
    public ?int $platformId = null;

    /**
     * Handle the platform filter event.
     */
    #[On('filterByPlatform')]
    public function filterByPlatform(int $platformId): void
    {
        $this->platformId = $platformId > 0 ? $platformId : null;
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $dealsQuery = Deal::query()
            ->with('platform')
            ->visible()
            ->inStock()
            ->recentlyUpdated()
            ->latest();

        if ($this->platformId) {
            $dealsQuery->where('platform_id', $this->platformId);
        }

        return view('livewire.result-list', [
            'deals' => $dealsQuery->get(),
        ]);
    }
}
