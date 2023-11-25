<?php

namespace Database\Seeders;

use App\Models\Spot;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $max = Spot::MAX_SPACE;

        for ($i = 1; $i <= $max; $i++) {
            Spot::firstOrCreate(['name' => 'SPOT-' . $i],
                [ 'code' => $this->generateRandomString()]
            );
        }
    }

    private function generateRandomString($length = 5) {
        $randomString = Str::random($length);

        $randomString[rand(0, $length - 1)] = rand(0, 9);

        return $randomString;
    }
}
