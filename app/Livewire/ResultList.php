<?php

namespace App\Livewire;

use App\Models\Deal;
use Livewire\Component;

class ResultList extends Component
{
    public function render()
    {
        $deals = Deal::orderBy('created_at', 'desc')
            ->where('updated_at', '>=', now()->subMinutes(5))
            ->where('products_left', '>', 0)
            ->get();

        return view('livewire.result-list', compact('deals'));
    }
}
