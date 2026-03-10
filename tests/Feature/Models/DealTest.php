<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DealTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the inStock scope.
     */
    public function test_scope_in_stock(): void
    {
        Deal::factory()->create(['products_left' => 10]);
        Deal::factory()->create(['products_left' => 0]);

        $this->assertEquals(1, Deal::inStock()->count());
    }

    /**
     * Test the fresh scope.
     */
    public function test_scope_fresh(): void
    {
        Deal::factory()->create(['updated_at' => now()]);
        Deal::factory()->create(['updated_at' => now()->subMinutes(6)]);

        $this->assertEquals(1, Deal::recentlyUpdated()->count());
    }

    /**
     * Test the visible scope for guest users.
     */
    public function test_scope_visible_for_guest(): void
    {
        Deal::factory()->create(['invalid' => false]);
        Deal::factory()->create(['invalid' => true]);

        $this->assertEquals(1, Deal::visible()->count());
    }

    /**
     * Test the platform relationship.
     */
    public function test_deal_has_platform_relationship(): void
    {
        $platform = Platform::factory()->create();
        $deal = Deal::factory()->create(['platform_id' => $platform->id]);

        $this->assertInstanceOf(Platform::class, $deal->platform);
        $this->assertEquals($platform->id, $deal->platform->id);
    }
}
