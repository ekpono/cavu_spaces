<?php

// write endpoints to check availability and prices

use App\Actions\ParkingAvailability;
use Illuminate\Support\Facades\Route;

$sanctum = 'auth:sanctum';

Route::prefix('v1/parking')->middleware($sanctum)->group(function () {

    Route::get('/availability', ParkingAvailability::class);

    Route::get('/prices', \App\Actions\ParkingPrice::class);
});
