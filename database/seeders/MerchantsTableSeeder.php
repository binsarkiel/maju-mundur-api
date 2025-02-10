<?php

namespace Database\Seeders;

use App\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Merchant::factory(5)->create();
        
        Merchant::create([
            'name' => 'Test Merchant',
            'email' => 'merchant@example.com',
            'password' => bcrypt('password'),
        ]);
    }
}
