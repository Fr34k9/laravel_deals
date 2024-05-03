<?php

namespace App\Livewire;

use App\Models\Deal;
use Livewire\Attributes\On;
use Livewire\Component;

class ResultList extends Component
{
    private $filter_by_platform = false;
    private $filter_by_platform_name = '';

    #[On('filterByPlatform')]
    public function filterByPlatform($platformId)
    {
        if($platformId === 0) {
            $this->filter_by_platform = false;
            $this->filter_by_platform_name = '';
            return;
        }

        $this->filter_by_platform = $platformId;
        $this->filter_by_platform_name = Deal::find($platformId)->platforms->name;
    }

    public function render()
    {
        $deals = Deal::orderBy('created_at', 'desc')
            ->where('products_left', '>', 0);

        $deals = $deals->where('updated_at', '>=', now()->subMinutes(10));

        if ($this->filter_by_platform) {
            $deals = $deals->where('platforms_id', $this->filter_by_platform);
        }

        $deals = $deals->get();

        return view('livewire.result-list', compact('deals'));
    }
}
