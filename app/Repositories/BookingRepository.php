<?php

namespace App\Repositories;

use App\Models\BookingTransaction;
use App\Repositories\Contracts\BookingRepositoryInterface;

class BookingRepository implements BookingRepositoryInterface
{
    public function createBooking(array $data): ?BookingTransaction
    {
        return BookingTransaction::create($data);
    }

    public function findByTrxIdAndPhoneNumber($bookingTrxId, $phoneNumber): ?BookingTransaction
    {
        return BookingTransaction::where('booking_trx_id', $bookingTrxId)
                                    ->where('phone_number', $phoneNumber)
                                    ->first();
    }
}
