<?php

namespace App\Actions;

use App\Http\Requests\CheckAvailabilityRequest;
use App\Services\Booking;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class ParkingPrice
{
    use AsAction;

    public function handle(
        Booking $bookingService,
        CheckAvailabilityRequest $request
    )
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $price = $bookingService->calculatePrice($startDate, $endDate);

        return response()->json([
            'data' => [
                'amount' => number_format($price / 100, 2),
                'currency' => config('app.default_currency')
            ],
            'message' => 'Successfully fetched'
        ]);
    }
}
