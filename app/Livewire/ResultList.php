<?php

namespace App\Livewire;

use App\Models\Deal;
use Livewire\Component;

class ResultList extends Component
{
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

        $deals = $deals->get();

        return view('livewire.result-list', compact('deals'));
    }
}
