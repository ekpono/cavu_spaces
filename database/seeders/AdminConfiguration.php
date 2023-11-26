<?php

namespace Database\Seeders;

use App\Models\SystemConfiguration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminConfiguration extends Seeder
{
    use WithoutModelEvents;

    /**
     * System configuration.
     *
     * Default currency is GBP.
     *
     * In a multi currency/location system, we would have to store different configuration
     * with different currencies in the database.
     *
     * Run the database seeds.
     */
    public function run(): void
    {
        $configurations = [
            [
                'key' => 'base_price', // Weekdays price
                'value' => 2000,
            ],
            [
                'key' => 'weekend_price_multiplier',
                'value' => 1.1,
            ],
            [
                'key' => 'summer_price_multiplier',
                'value' => 1.2,
            ],
            [
                'key' => 'winter_price_multiplier',
                'value' => 0.2,
            ],
        ];

        foreach ($configurations as $configuration) {
            SystemConfiguration::create($configuration);
        }
    }
}
