<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Deal;
use App\Models\Platform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    protected $model = Deal::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productsTotal = $this->faker->randomNumber(2);
        $productsLeft = $this->faker->numberBetween(0, $productsTotal);

        return [
            'platform_id' => Platform::factory(),
            'title' => $this->faker->words($this->faker->numberBetween(1, 4), true),
            'subtitle' => $this->faker->words($this->faker->numberBetween(1, 4), true),
            'price' => $this->faker->randomFloat(2, 5, 1000),
            'else_price' => $this->faker->randomFloat(2, 5, 1500),
            'products_total' => $productsTotal,
            'products_left' => $productsLeft,
            'image' => $this->faker->imageUrl(),
            'url' => $this->faker->url(),
            'invalid' => $this->faker->boolean(5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
