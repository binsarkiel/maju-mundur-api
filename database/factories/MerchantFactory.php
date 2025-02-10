<?php

namespace Database\Factories;

use App\Models\Merchant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Merchant>
 */
class MerchantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
     protected $model = Merchant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(), // Use a company name
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // Always hash passwords!
            // Don't include timestamps; Eloquent handles them automatically
        
        ];
    }
}
