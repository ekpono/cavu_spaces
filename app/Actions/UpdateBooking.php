<?php

namespace App\Actions;

use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\Booking as BookingService;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateBooking
{
    use AsAction;

    public function handle(
        Booking $booking,
        UpdateBookingRequest $request,
        BookingService $bookingService
    ){
        // User should not update booking if it is not theirs
        if (auth()->user()->cannot('update', $booking)) {
            return response()->json([
                'message' => 'Unauthorized action'
            ]);
        }

        $data = $request->validated();

        if (isset($data['start_date']) && isset($data['end_date'])) {

            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);

            $availableSpot = $bookingService->getAvailableSpot(
                $startDate,
                $endDate,
                false,
                $booking
            );

            // if there is no available spot
            if (empty($availableSpot)) {
                return response()->json([
                    'message' => 'Sorry there is no available spot!',
                ], 400);
            }

            $data['spot_id'] = $availableSpot->id;

            // calculate total price
            $totalPrice = $bookingService->calculatePrice(
                $startDate,
                $endDate
            );

            $data['total_price'] = $totalPrice;

            // Send receipt
            $booking->sendReceipt();
        }

        $booking->update($data);

        return response()->json([
            'message' => 'Booking updated successfully',
            'data' => BookingResource::make($booking),
        ]);
    }
}
