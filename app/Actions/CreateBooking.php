<?php

namespace App\Actions;

use App\Http\Requests\CreateBookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\Booking as BookingService;
use Illuminate\Support\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateBooking
{
    use AsAction;

    public function handle(
        CreateBookingRequest $request,
        BookingService $bookingService
    ){
        $data = $request->validated();

        $startDate = Carbon::parse($data['start_date']);
        $endDate = Carbon::parse($data['end_date']);

        $availableSpot = $bookingService->getAvailableSpot($startDate, $endDate);

        if (empty($availableSpot)) {
            return response()->json([
                'message' => 'Sorry there is no available spot!',
            ], 400);
        }

        $totalPrice = $bookingService->calculatePrice($startDate, $endDate);

        $booking = Booking::create([
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'user_id' => auth()->id(),
            'total_price' => $totalPrice,
            'spot_id' => $availableSpot->id,
            'plate_number' => $data['plate_number'],
        ]);

        // Mark as paid
        // Usually, this would be done after payment is confirmed
        $booking->markAsPaid();

        // Email receipt confirmation
        $booking->sendReceipt();

        return response()->json([
            'data' => BookingResource::make($booking),
            'message' => 'Booking created successfully',
        ]);
    }
}
