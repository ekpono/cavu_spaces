<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendPaymentReceipt extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     *
     * Create a new notification instance.
     */
    public function __construct(private Booking $booking)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Receipt attachment for the email.
     *
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting("Hello {$this->booking->user->name}")
            ->subject('Payment Receipt')
            ->line('Thank you for booking with us.')
            ->line('Please find attached your payment receipt.')
            ->line('Thank you for using our application!')
            ->attachData($this->getReceipt(), 'receipt.pdf', [
                'mime' => 'application/pdf',
            ]);
    }

    public function getReceipt()
    {
        $pdf = \PDF::loadView('docs.receipt', ['booking' => $this->booking]);
        return $pdf->output();
    }
}
