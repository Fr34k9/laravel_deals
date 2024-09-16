<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            ['name' => 'Daydeal', 'url' => 'https://daydeal.ch', 'image' => 'https://www.daydeal.ch/favicon.svg'],
            ['name' => 'Blick', 'url' => 'https://www.blick.ch', 'image' => 'https://www.blick.ch/favicon.ico'],
            ['name' => 'Qoqa', 'url' => 'https://www.qoqa.ch', 'image' => 'https://www.qoqa.ch/favicon.ico'],
            ['name' => 'Digitec', 'url' => 'https://www.digitec.ch', 'image' => 'https://www.digitec.ch/favicon.ico'],
            ['name' => 'Galaxus', 'url' => 'https://www.galaxus.ch', 'image' => 'https://www.galaxus.ch/favicon.ico'],
            ['name' => '20min', 'url' => 'https://myshop.20min.ch/api/proxy/shop/deals', 'image' => 'https://www.20min.ch/favicon.ico'],
        ];

        foreach ($platforms as $platform) {
            Platform::where('name', $platform['name'])->first() ?: 
                Platform::create($platform);
        }
    }
}
