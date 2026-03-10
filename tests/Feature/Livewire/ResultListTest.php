<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\ResultList;
use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ResultListTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the component renders and shows deals.
     */
    public function test_it_renders_successfully(): void
    {
        Livewire::test(ResultList::class)
            ->assertStatus(200);
    }

    /**
     * Test filtering deals by platform.
     */
    public function test_it_filters_deals_by_platform(): void
    {
        $platformA = Platform::factory()->create(['name' => 'Platform A']);
        $platformB = Platform::factory()->create(['name' => 'Platform B']);

        $dealA = Deal::factory()->create([
            'platform_id' => $platformA->id,
            'updated_at' => now(),
            'products_left' => 10,
        ]);
        
        $dealB = Deal::factory()->create([
            'platform_id' => $platformB->id,
            'updated_at' => now(),
            'products_left' => 10,
        ]);

        Livewire::test(ResultList::class)
            ->dispatch('filterByPlatform', $platformA->id)
            ->assertSet('platformId', $platformA->id)
            ->assertViewHas('deals', function ($deals) use ($dealA, $dealB) {
                return $deals->contains($dealA) && !$deals->contains($dealB);
            });
    }

    /**
     * Test that out of stock deals are not shown.
     */
    public function test_it_does_not_show_out_of_stock_deals(): void
    {
        $deal = Deal::factory()->create([
            'products_left' => 0,
            'updated_at' => now(),
        ]);

        Livewire::test(ResultList::class)
            ->assertViewHas('deals', function ($deals) use ($deal) {
                return !$deals->contains($deal);
            });
    }

    /**
     * Test that old deals (not fresh) are not shown.
     */
    public function test_it_does_not_show_old_deals(): void
    {
        $deal = Deal::factory()->create([
            'updated_at' => now()->subMinutes(10),
            'products_left' => 10,
        ]);

        Livewire::test(ResultList::class)
            ->assertViewHas('deals', function ($deals) use ($deal) {
                return !$deals->contains($deal);
            });
    }
}
