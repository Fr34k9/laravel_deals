<?php

namespace App\Livewire;

use App\Models\Deal;
use Livewire\Attributes\On;
use Livewire\Component;

class ResultList extends Component
{
    private $filter_by_platform;

    #[On('filterByPlatform')]
    public function filterByPlatform($platformId)
    {
        if($platformId === 0) {
            $this->filter_by_platform = false;
            return;
        }

        $this->filter_by_platform = $platformId;
    }

    public function render()
    {
        $deals = Deal::orderBy('created_at', 'desc')
            ->where('products_left', '>', 0);

        // if is local development
        if (config('app.env') === 'local') {
            $deals = $deals->where('updated_at', '>=', now()->subHours(12));
        } else {
            $deals = $deals->where('updated_at', '>=', now()->subMinutes(5));
        }

        if ($this->filter_by_platform) {
            $deals = $deals->where('platform_id', $this->filter_by_platform);
        }

        $deals = $deals->get();

        return view('livewire.result-list', compact('deals'));
    }
}
