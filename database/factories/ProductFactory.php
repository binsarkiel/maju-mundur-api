<?php

namespace Database\Factories;

use App\Models\Merchant;
use App\Models\Product;
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

     protected $model = Product::class;
     
    public function definition(): array
    {
        return [
            'merchant_id' => Merchant::factory(), // This is VERY important!
            'name' => $this->faker->sentence(3), // Short product name
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1, 1000), // Price between 1.00 and 1000.00
            'stock' => $this->faker->numberBetween(0, 100), // Stock between 0 and 100
        
        ];
    }
}
