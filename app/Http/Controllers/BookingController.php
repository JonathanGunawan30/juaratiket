<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\StoreCheckBookingRequest;
use App\Http\Requests\StorePaymentRequest;
use App\Models\BookingTransaction;
use App\Models\Ticket;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    public function booking(Ticket $ticket): View
    {
       return view('front.booking', compact('ticket'));
    }

    public function bookingStore(Ticket $ticket, StoreBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $totals = $this->bookingService->calculateTotals($ticket->id, $validated['total_participant']);
        $this->bookingService->storeBookingInSession($ticket, $validated, $totals);

        return redirect()->route('front.payment');
    }

    public function payment(): View
    {
        $data = $this->bookingService->payment();
        return view('front.payment', $data);
    }

    public function paymentStore(StorePaymentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $bookingTransactionId = $this->bookingService->paymentStore($validated);

        if($bookingTransactionId){
            return redirect()->route('front.booking_finished', $bookingTransactionId);
        }

        return redirect()->route('front.index')->withErrors(['error' => 'Payment failed. Please try again.']);
    }

    public function bookingFinished(BookingTransaction $bookingTransaction): View
    {
        return view('front.booking_finished', compact('bookingTransaction'));
    }

    public function checkBooking(): View
    {
        return view('front.check_booking');
    }

    public function checkBookingDetails(StoreCheckBookingRequest $request): View|RedirectResponse
    {
        $validated = $request->validated();

        $bookingDetails = $this->bookingService->getBookingDetails($validated);

        if($bookingDetails){
            return view('front.check_booking_details', compact('bookingDetails'));
        }
        return redirect()->route('front.check_booking')->with('error', 'Transaction not found');
    }
}
