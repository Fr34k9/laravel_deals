<?php

namespace Database\Seeders;

use App\Models\Deal;
use Illuminate\Database\Seeder;

class DealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Deal::factory(100)->create();
    }
}
