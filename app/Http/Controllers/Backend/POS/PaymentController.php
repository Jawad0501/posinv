<?php

namespace App\Http\Controllers\Backend\POS;

use App\Enum\PaymentMethod;
use App\Http\Controllers\Api\Backend\POS\PaymentController as ApiPaymentController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Backend\PaymentRequest;
use App\Models\Order;

class PaymentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $status = [PaymentMethod::CASH->value, PaymentMethod::CARD->value, PaymentMethod::GIFTCARD->value];
        $order = Order::with('user:id,rewards,rewards_available,discount,wallet')->findOrFail(request()->get('order_id'), ['id', 'user_id', 'discount', 'grand_total']);
        $finalize_order = [
            'grand_total'      => (float) $order->grand_total,
            // 'reward_amount'    => (float) setting('reward_exchange_rate') * $order->user->rewards_available ?? 0,
            'service_charge'   => (float) setting('service_charge'),
            'special_discount' => $order->user->discount ?? 0,
            'discount_amount'  => $order->discount ?? 0,
        ];
        
        return view('pages.pos.partials.payment', compact('order', 'status', 'finalize_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        $payment = new ApiPaymentController;
        $payment = $payment->__invoke($request, true, session()->get('pos_cart'));

        // dd($payment);
        if ($payment['status'] == true) {
            session()->forget('pos_cart');
            session()->forget('pos_discount');
            return response()->json([
                'print_url' => route('pos.order.print', $payment['order_id']),
                'message' => 'Payment successfully done.',
            ]);
        }
    }
}
