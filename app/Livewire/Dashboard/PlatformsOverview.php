<?php

namespace App\Livewire\Dashboard;

use App\Models\Platform;
use Livewire\Component;

class PlatformsOverview extends Component
{
    public function togglePlatform($platformId)
    {
        $platform = Platform::find($platformId);
        $platform->active = !$platform->active;
        $platform->save();
    }

    public function render()
    {
        $platforms = Platform::all();
        return view('livewire.dashboard.platforms-overview', compact('platforms'));
    }
}
