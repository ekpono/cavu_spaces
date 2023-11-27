<?php

test('get authenticated user created paginated bookings', function () {

    login()->getJson('/api/v1/bookings')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'spot_id',
                    'start_date',
                    'end_date',
                    'created_at',
                    'updated_at',
                ]
            ],
            'links' => [
                'first',
                'last',
                'prev',
                'next',
            ],
            'meta' => [
                'current_page',
                'from',
                'last_page',
                'links',
                'path',
                'per_page',
                'to',
                'total',
            ]
        ]);
});
