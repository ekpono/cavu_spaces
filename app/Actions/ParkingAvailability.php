<?php

namespace App\Actions;

use App\Http\Requests\CheckAvailabilityRequest;
use App\Services\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class ParkingAvailability
{
    use AsAction;

    public function handle(
        Booking $bookingService,
        CheckAvailabilityRequest $request
    )
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $cacheKey = "parking-availability-{$startDate->format('Y-m-d')}-{$endDate->format('Y-m-d')}";

        $availableSpot = Cache::remember($cacheKey, 60 * 60 * 24, function () use ($bookingService, $startDate, $endDate) {
            $spots = $bookingService->getAllSpots();
            return $bookingService->getSingleAvailableSpot($spots, $startDate, $endDate);
        });

        $message = $availableSpot ? 'There is a free spot!!!💃' : 'No available spots';

        return response()->json([
           'data' => $availableSpot,
           'message' => $message
        ]);
    }
}
