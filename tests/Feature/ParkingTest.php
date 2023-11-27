<?php

beforeEach(function () {
    $this->seed();
});

test('test that authenticated user can view parking prices', function () {

    $startDate = now()->addDays(1)->format('Y-m-d');
    $endDate = now()->addDays(2)->format('Y-m-d');

    $this->getJson("/api/v1/parking/prices?start_date={$startDate}&end_date={$endDate}")
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'amount',
                'currency'
            ],
            'message'
        ]);
});

test('test that authenticated user can view parking availability', function () {

    $startDate = now()->addDays(1)->format('Y-m-d');
    $endDate = now()->addDays(2)->format('Y-m-d');

    $this->getJson("/api/v1/parking/availability?start_date={$startDate}&end_date={$endDate}")
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'code',
                'created_at',
                'updated_at'
            ],
            'message'
        ]);

});

test('test that authenticated user can view parking spots', function () {

    $startDate = now()->addDays(1)->format('Y-m-d');

    $this->getJson("/api/v1/parking/spots?date={$startDate}")
        ->assertOk()
        ->assertJsonStructure([
            'message',
            'spots'
        ]);

});
