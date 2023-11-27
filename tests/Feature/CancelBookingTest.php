<?php

use App\Models\Booking;


test('creator of booking should be able to delete their booking', function () {
    $login = login();


    $booking = Booking::factory()->create([
        'user_id' => auth()->id()
    ]);

    $login->deleteJson("/api/v1/bookings/{$booking->id}")
        ->assertOk()
        ->assertJson([
            'message' => 'Booking cancelled'
        ]);

    $this->assertSoftDeleted('bookings', [
        'id' => $booking->id
    ]);
});

test('user that did not create booking should not be authorized to delete booking', function () {

    $booking = Booking::factory()->create();

    login()->deleteJson("/api/v1/bookings/{$booking->id}")
        ->assertOk()
        ->assertJson([
            'message' => 'Unauthorized action'
        ]);
});
