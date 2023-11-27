<?php

use App\Actions\ParkingAvailability;
use App\Actions\ParkingPrice;
use Illuminate\Support\Facades\Route;

$sanctum = 'auth:sanctum';

Route::prefix('v1/parking')->withoutMiddleware($sanctum)->group(function () {

    Route::get('/prices', ParkingPrice::class);

    Route::get('/availability', ParkingAvailability::class);
});
