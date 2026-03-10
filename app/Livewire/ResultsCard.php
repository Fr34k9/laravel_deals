<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Deal;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ResultsCard extends Component
{
    /**
     * The deal model instance.
     *
     * @var Deal
     */
    public Deal $deal;

    /**
     * Whether the deal should be blurred.
     *
     * @var bool
     */
    public bool $blur = false;

    /**
     * Mount the component.
     */
    public function mount(Deal $deal): void
    {
        $this->deal = $deal;
        $this->blur = (bool) $deal->invalid;
    }

    /**
     * Toggle the visibility of the deal.
     */
    public function toggleDealVisibility(): void
    {
        $this->deal->update([
            'invalid' => !$this->deal->invalid,
        ]);
        
        $this->blur = (bool) $this->deal->invalid;
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.results-card');
    }
}
