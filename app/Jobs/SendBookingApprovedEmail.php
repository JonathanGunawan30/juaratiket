<?php

namespace App\Jobs;

use App\Mail\OrderApproved;
use App\Models\BookingTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendBookingApprovedEmail implements ShouldQueue
{
    use Queueable;
    protected $booking;

    /**
     * Create a new job instance.
     */
    public function __construct(BookingTransaction $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->booking->email)->send(new OrderApproved($this->booking));
    }
}
