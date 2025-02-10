<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = Merchant::all();

        if ($merchants->count() > 0) {
            Product::factory(20)->create([
                'merchant_id' => $merchants->random()->id,
            ]);
        }
    }
}
