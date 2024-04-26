<?php

namespace App\Livewire;

use App\Models\Deal;
use Livewire\Component;

class ResultList extends Component
{
    public function render()
    {
        $deals = Deal::orderBy('created_at', 'desc')
            ->whereTime('updated_at', '>=', now()->subHours(1))
            ->get();

        return view('livewire.result-list', compact('deals'));
    }
}
