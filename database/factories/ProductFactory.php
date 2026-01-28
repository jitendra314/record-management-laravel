<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{

    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'        => $this->faker->words(3, true),
            'description'  => $this->faker->paragraph,
            'price'        => $this->faker->randomFloat(2, 100, 5000),
            'available_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
