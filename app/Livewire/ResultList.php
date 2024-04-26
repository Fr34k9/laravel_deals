<?php

namespace App\Livewire;

use App\Models\Deal;
use Livewire\Component;

class ResultList extends Component
{
    public function render()
    {
        $deals = Deal::all();

        return view('livewire.result-list', compact('deals'));
    }
}
