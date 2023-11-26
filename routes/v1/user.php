<?php

use App\Actions\User\Login;
use App\Actions\User\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/user')->group(function () {
    $sanctum = 'auth:sanctum';

    Route::get('/', fn (Request $request) => $request->user());

    Route::post('login', Login::class)->withoutMiddleware($sanctum);
    Route::post('register', Register::class)->withoutMiddleware($sanctum);
});
