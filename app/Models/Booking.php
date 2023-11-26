<?php

namespace App\Models;

use App\Notifications\SendPaymentReceipt;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Money\Money;

class Booking extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'start_date', 'end_date', 'total_price',
        'spot_id', 'plate_number', 'user_id',
    ];

    protected $casts = [
        'start_time' => 'date',
        'stop_time' => 'date',
    ];

    /**
     * This method probably could be called after webhook payment confirmation
     * from the payment service provider after successful payment.
     *
     * For the sake of simplicity, we are calling it here.
     *
     * Mark booking as paid.
     */
    public function markAsPaid()
    {
        if (is_null($this->paid_at)) {
            $this->forceFill(['paid_at' => $this->freshTimestamp()])->save();
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sendReceipt()
    {
        $this->user->notify(new SendPaymentReceipt($this));
    }

    /**
     * Get price in GBP.
     *
     * We are using MoneyPHP library to handle money
     * and also assuming that the price is in GBP.
     */
    public function getPrice(): string
    {
        $amount = Money::GBP($this->total_price);

        return number_format($amount->getAmount() / 100, 2);
    }

    /**
     * Get receipt as PDF.
     *
     * We are using DomPDF library to generate PDF.
     */
    public function getReceipt()
    {
        return view ('receipt', [
            'booking' => $this,
        ])->render();
    }
}
