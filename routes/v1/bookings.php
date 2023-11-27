<?php

use App\Actions\CancelBooking;
use App\Actions\CreateBooking;
use App\Actions\GetBookings;
use App\Actions\UpdateBooking;
use Illuminate\Support\Facades\Route;

$sanctum = 'auth:sanctum';

Route::prefix('v1/bookings')->middleware($sanctum)->group(function () {

    Route::get('/', GetBookings::class);

    Route::post('/', CreateBooking::class);

    Route::put('/{booking}', UpdateBooking::class);

    Route::delete('/{booking}', CancelBooking::class);

});
