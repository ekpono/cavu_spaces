<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => self::factoryForModel(User::class),
            'spot_id' => 1,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(2),
            'total_price' => 1000,
            'plate_number' => 'ABC123'
        ];
    }
}
