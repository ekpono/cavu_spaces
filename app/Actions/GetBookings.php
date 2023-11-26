<?php

namespace App\Actions;

use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Lorisleiva\Actions\Concerns\AsAction;

class GetBookings
{
    use AsAction;

    public function handle()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->paginate();

        return BookingResource::collection($bookings);
    }
}
