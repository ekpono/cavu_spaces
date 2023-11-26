<?php

namespace App\Actions;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Lorisleiva\Actions\Concerns\AsAction;

class CancelBooking
{
    use AsAction;

    /**
     * Cancel a booking
     *
     * @param Booking $booking
     * @return JsonResponse
     */
    public function handle(Booking $booking)
    {
        // User should not delete booking if it is not theirs
        if (auth()->user()->cannot('delete', $booking)) {
            return response()->json([
               'message' => 'Unauthorized action'
            ]);
        }

        // soft delete the booking
        $booking->delete();

        return response()->json(['message' => 'Booking cancelled']);
    }
}
