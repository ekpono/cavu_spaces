<?php


use App\Models\Booking;
use App\Models\Spot;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;

uses(WithFaker::class);

beforeEach(function () {
    $this->seed();
});

test('it creates a booking with available spot and calculates price', function () {

    $startDate = now()->addDays()->toDateString();
    $endDate = now()->addDays(3)->toDateString();

    login()->postJson('/api/v1/bookings', [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'plate_number' => 'ABC123',
    ])->assertOk();

    $booking = Booking::latest()->first();

    expect($booking)->not->toBeNull()
        ->and($booking->total_price)->toBeString()->toBe("600000")
        ->and($booking->spot_id)->toBe(1)->toBeInt();
});

test('only authenticated users can create bookings', function () {

    $startDate = now()->addDays()->toDateString();
    $endDate = now()->addDays(3)->toDateString();

    $this->postJson('/api/v1/bookings', [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'plate_number' => 'ABC123',
    ])->assertUnauthorized();
});

test('it does not create a booking with unavailable spot', function () {

    $startDate = now()->addDays()->toDateString();
    $endDate = now()->addDays(3)->toDateString();

    // make 10 requests to book all the spots and max out
    $availableSpot = Spot::MAX_SPACE;

    for ($i = 0; $i < $availableSpot; $i++) {
        login()->postJson('/api/v1/bookings', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'plate_number' => 'ABC123',
        ])->assertOk();
    }

    login()->postJson('/api/v1/bookings', [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'plate_number' => 'ABC123',
    ])->assertStatus(Response::HTTP_FOUND)
        ->assertJson([
            'message' => 'Sorry there is no available spot!',
        ]);
});
