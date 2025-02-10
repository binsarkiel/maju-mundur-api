<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RewardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Reward::create([
            'name' => 'Reward A',
            'description' => 'Description of Reward A',
            'required_points' => 20,
        ]);

        Reward::create([
            'name' => 'Reward B',
            'description' => 'Description of Reward B',
            'required_points' => 40,
        ]);
    }
}
