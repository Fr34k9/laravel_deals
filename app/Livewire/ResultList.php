<?php

namespace App\Livewire;

use App\Models\Deal;
use App\Models\Platform;
use Livewire\Attributes\On;
use Livewire\Component;

class ResultList extends Component
{
    private Platform $platform;

    #[On('filterByPlatform')]
    public function filterByPlatform($platformId)
    {
        if( $platformId < 1 ) {
            return;
        }

        $this->platform = Platform::find($platformId);
    }

    public function render()
    {
        $deals = Deal::orderBy('created_at', 'desc')
            ->where('products_left', '>', 0);

        $deals = $deals->where('updated_at', '>=', now()->subMinutes(5));

        if (!empty($this->platform)) {
            $deals = $deals->where('platforms_id', $this->platform->id);
        }

        $deals = $deals->get();

        return view('livewire.result-list', compact('deals'));
    }
}
