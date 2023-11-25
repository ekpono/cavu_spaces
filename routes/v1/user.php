<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/user')->group(function() {
    $sanctum = 'auth:sanctum';

    Route::post('login', \App\Actions\User\Login::class)->withoutMiddleware($sanctum);
    Route::post('register', \App\Actions\User\Register::class)->withoutMiddleware($sanctum);
});
