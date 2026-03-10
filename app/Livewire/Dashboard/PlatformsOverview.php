<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Platform;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class PlatformsOverview extends Component
{
    /**
     * Toggle the active status of a platform.
     */
    public function togglePlatform(int $platformId): void
    {
        $platform = Platform::find($platformId);
        
        if ($platform) {
            $platform->update([
                'active' => !$platform->active,
            ]);
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.dashboard.platforms-overview', [
            'platforms' => Platform::all(),
        ]);
    }
}
