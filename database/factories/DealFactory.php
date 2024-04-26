<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $products_total = $this->faker->randomNumber(2);
        $products_left = $this->faker->numberBetween(0, $products_total);

        $platform_id = PlatformFactory::new()->create()->id;

        return [
            'platform_id' => $platform_id,
            'title' => $this->faker->words($this->faker->numberBetween(1,5), true),
            'subtitle' => $this->faker->words($this->faker->numberBetween(1,5), true),
            'price' => $this->faker->randomFloat(2, 0, 100),
            'else_price' => $this->faker->randomFloat(2, 0, 100),
            'products_total' => $products_total,
            'products_left' => $products_left,
            'image' => $this->faker->imageUrl(),
            'url' => $this->faker->url,
            'invalid' => $this->faker->boolean(10),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
