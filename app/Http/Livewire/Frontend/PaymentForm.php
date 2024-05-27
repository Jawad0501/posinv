<?php

namespace App\Http\Livewire\Frontend;

use App\Http\Controllers\Frontend\CheckoutController;
use App\Mail\OrderConfirmationMail;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class PaymentForm extends Component
{
    public function render()
    {
        return view('livewire.frontend.payment-form');
    }

    /**
     * payment store
     *
     * @return void
     */
    public function store(Request $request)
    {
        $this->validate([
            'name' => 'required|string',
            'number' => 'required|max:20',
            'cvc' => 'required|max:4',
            'expiry_month' => 'required|max:2',
            'expiry_year' => 'required|max:4',
        ]);

        if (! session()->has('input')) {
            return response()->json([
                'message' => 'Something went wrong. Please try again later.',
            ], 300);
        }

        $order = CheckoutController::order();
        $amount = $order->grand_total;

        $payment = Stripe::payment($request, $amount);
        if ($payment == false) {
            return $payment;
        }

        Payment::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'method' => 'Stripe',
            'reward_amount' => $order->rewards_amount,
            'give_amount' => $amount,
            'change_amount' => 0,
            'grand_total' => $amount,
        ]);

        session()->put('order_done', true);

        Mail::to(auth()->user()->email)->send(new OrderConfirmationMail($order));

        return response()->json([
            'redirect' => route('home'),
            'message' => 'Payment successfully confirmed, thank you.',
        ]);
    }
}
