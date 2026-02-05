<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        return [
            //
            'name' => ucfirst($name),
            'slug' => \Str::slug($name),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(10000, 2000000),
            'stock' => $this->faker->numberBetween(10, 100),
            'is_active' => $this->faker->boolean(80),
            'image' => null

        ];
    }
}
