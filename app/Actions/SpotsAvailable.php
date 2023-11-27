<?php

namespace App\Actions;

use App\Http\Requests\CheckSpotAvailability;
use App\Services\Booking;
use Carbon\Carbon;
use Lorisleiva\Actions\Concerns\AsAction;

class SpotsAvailable
{
    use AsAction;

    public function handle(
        CheckSpotAvailability $request,
        Booking $bookingService
    )
    {
        $start = Carbon::parse($request->date);
        $endDate = Carbon::parse($request->date)->addDays(1);

        $spots = $bookingService->getAllSpots();

        $spots = $bookingService->getAllAvailableSpots($spots, $start, $endDate);

        $count = count($spots) ?? 0;

        $message =  "{$count} spots available on the {$start->format('d/m/Y')}";

        return response()->json([
           'message' => $message,
           'spots' => $count
        ]);
    }
}
