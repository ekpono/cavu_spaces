<?php

namespace App\Services;

use App\Models\Booking as BookingModel;
use App\Models\Spot;
use App\Models\SystemConfiguration;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Money\Currency;
use Money\Money;

class Booking
{
    private string $defaultCurrency;

    public function __construct()
    {
        $this->defaultCurrency = config('app.default_currency');
    }

    /**
     * Get available spots based on the provided criteria.
     *
     * @return Spot|Spot[]|null
     */
    public function getAvailableSpot(Carbon $startDate, Carbon $endDate, bool $allSpots = false)
    {
        $spots = $this->getAllSpots();

        if ($allSpots) {
            return $this->getAllAvailableSpots($spots, $startDate, $endDate);
        }

        return $this->getSingleAvailableSpot($spots, $startDate, $endDate);
    }

    /**
     * Calculate the price for the given date range.
     */
    public function calculatePrice(Carbon $startDate, Carbon $endDate)
    {
        // Get system configuration
        $systemConfig = $this->getSystemConfiguration();

        // Retrieve configuration values or provide defaults
        $basePrice = $systemConfig->get('base_price', 0); // Default to 0 if not found
        $weekendPriceMultiplier = $systemConfig->get('weekend_price_multiplier', 0);
        $summerPriceMultiplier = $systemConfig->get('summer_price_multiplier', 0);
        $winterPriceMultiplier = $systemConfig->get('winter_price_multiplier', 0);

        $price = 0;

        // Loop through each day in the date range
        for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate->addDay()) {

            // Check if the current day is a weekend
            $dailyPrice = $currentDate->isWeekend()
                ? ($basePrice + $this->calculatePercentage($basePrice, $weekendPriceMultiplier))
                : $basePrice;

            // Check if the current day is in summer
            if ($currentDate->month >= 6 && $currentDate->month <= 9) {
                $dailyPrice += ($basePrice + $this->calculatePercentage($basePrice, $summerPriceMultiplier));
            }

            // Check if the current day is in winter
            if ($currentDate->month >= 12 || $currentDate->month <= 2) {
                $dailyPrice += ($basePrice + $this->calculatePercentage($basePrice, $winterPriceMultiplier));
            }

            $price += $dailyPrice;
        }

        $price = new Money($price * 100, new Currency($this->defaultCurrency));

        return $price->getAmount();
    }

    /**
     * Get a single available spot.
     *
     * @param  Spot[]  $spots
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     * @return Spot|null
     */
    private function getSingleAvailableSpot($spots, $startDate, $endDate)
    {
        foreach ($spots as $spot) {
            if (! $this->isBooked($spot, $startDate, $endDate)) {
                return $spot;
            }
        }

        return null;
    }

    /**
     * Get all available spots.
     *
     * @param  Spot[]  $spots
     * @param  Carbon  $startDate
     * @param  Carbon  $endDate
     * @return Spot[]
     */
    private function getAllAvailableSpots($spots, $startDate, $endDate)
    {
        $availableSpots = [];

        foreach ($spots as $spot) {
            if (! $this->isBooked($spot, $startDate, $endDate)) {
                $availableSpots[] = $spot;
            }
        }

        return $availableSpots;
    }

    /**
     * Check if a spot is booked for the given date range.
     *
     * @return bool
     */
    private function isBooked(Spot $spot, Carbon $startDate, Carbon $endDate)
    {
        return BookingModel::query()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate]);
            })
            ->where('spot_id', $spot->id)
            ->exists();
    }

    /**
     * Get all spots from cache.
     *
     * NB: This is a very simple implementation of caching.
     * The cache should be cleared when a new spot is added or an existing spot is updated.
     *
     * @return Spot[]
     */
    private function getAllSpots()
    {
        return Cache::rememberForever('available_spots', function () {
            return Spot::all();
        });
    }

    /**
     * Get system configuration from cache.
     */
    private function getSystemConfiguration()
    {
        return Cache::rememberForever('system_configuration', function () {
            return SystemConfiguration::pluck('value', 'key');
        });
    }

    private function calculatePercentage($amount, $percentage)
    {
        return $amount * ($percentage / 100);
    }
}
