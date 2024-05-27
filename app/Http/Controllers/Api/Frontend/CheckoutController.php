<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Enum\PaymentMethod;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\PaymentController;
use App\Http\Requests\Frontend\CheckoutRequest;
use App\Mail\OrderConfirmationMail;
use App\Models\Addon;
use App\Models\Coupon;
use App\Models\Food;
use App\Models\Order;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * @group Checkout Management
 *
 * APIs to manage checkout
 */
class CheckoutController extends Controller
{
    /**
     * Store a new order in storage.
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Order has been processed successfully.'
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *   "message": "The name field is required. (and 2 more errors)"
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function store(CheckoutRequest $request)
    {
        $service_charge = setting('service_charge');
        $rewards = $request->user()->rewards_available;
        $delivery_charge = $request->shipping_method == 'delivery' ? setting('delivery_charge') : 0;
        $reward_amount = $request->has('use_reward') ? ($rewards * setting('reward_exchange_rate')) : 0;
        $discount = $request->discount;
        $sub_total = (cartDataGrandTotal($request->items) - $discount);
        $grand_total = round(($sub_total + $service_charge + $delivery_charge) - $reward_amount, 2);

        $trx_id = getTrx();

        $payment = new PaymentController;
        $message = '';
        $redirect = '';
        $btc_wallet = null;

        if ($request->payment_method == PaymentMethod::STRIPE->value) {
            $response = $payment->stripe($grand_total, $trx_id, ['is_api' => rand()]);

            if (isset($response->error)) {
                $message = $response->message;
            } else {
                $redirect = $response->session->url;
                $btc_wallet = $response?->session?->id;
            }
        }
        if ($request->payment_method == PaymentMethod::PAYPAL->value) {
            $response = $payment->paypal($grand_total, $trx_id, ['is_api' => rand()]);
            if (isset($response['id']) && $response['id'] != null) {
                $btc_wallet = $response['id'];
                $isRel = false;
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        $redirect = $links['href'];
                        $isRel = true;
                    }
                }
                if (! $isRel) {
                    $message = 'Something went wrong, please try again.';
                }
            } else {
                $message = 'Something went wrong, please try again.';
            }
        }

        if ($message != '') {
            return response()->json(['message' => $message], 300);
        }
        if ($reward_amount > 0) {
            $request->user()->fill(['rewards_available' => 0])->save();
        }

        $order = Order::create([
            'user_id' => $request->user()->id,
            'type' => 'Online',
            'order_by' => $request->user()->full_name,
            'discount' => $discount,
            'rewards_amount' => $reward_amount,
            'service_charge' => $service_charge,
            'delivery_charge' => $delivery_charge,
            'delivery_type' => $request->shipping_method,
            'address' => $request->user()->address_book[$request->address_book],
            'note' => $request->note,
            'date' => date('Y-m-d'),
        ]);

        foreach ($request->items as $item) {
            $menu = Food::where('slug', $item['slug'])->first();
            $variant = Variant::find($item['variant_id'], ['id', 'price']);
            if ($menu) {
                $price = $variant->price ?? $menu->price;
                $total = $price * $item['quantity'];
                $total_vat = $menu->tax_vat * $item['quantity'];
                $tax_vat = ($total / 100) * $total_vat;

                $orderDetails = $order->orderDetails()->create([
                    'food_id' => $menu->id,
                    'variant_id' => $variant->id,
                    'processing_time' => $menu->processing_time,
                    'price' => $price,
                    'quantity' => $item['quantity'],
                    'vat' => $total_vat,
                    'total_price' => ($total + $tax_vat),
                ]);

                foreach ($item['addons'] as $itemAddon) {
                    $addon = Addon::find($itemAddon['id']);
                    if ($addon) {
                        $orderDetails->addons()->create([
                            'addon_id' => $addon->id,
                            'price' => $addon->price,
                            'quantity' => $itemAddon['quantity'],
                        ]);
                    }
                }
            }
        }

        $order->update([
            'grand_total' => $grand_total,
            'invoice' => generate_invoice($order->id),
        ]);

        $order->payment()->create([
            'user_id' => $request->user()->id,
            'method' => $request->payment_method,
            'reward_amount' => $reward_amount,
            'rewards' => $reward_amount > 0 ? $rewards : 0,
            'give_amount' => $grand_total,
            'grand_total' => $grand_total,
            'change_amount' => 0,
            'trx' => $trx_id,
            'btc_wallet' => $btc_wallet,
        ]);

        if (! in_array($request->payment_method, [PaymentMethod::STRIPE->value, PaymentMethod::PAYPAL->value])) {
            Mail::to($request->user()->email)->send(new OrderConfirmationMail($order));

            return response()->json(['message' => 'Order has been processed successfully']);
        }

        return response()->json(['redirect' => $redirect]);
    }

    /**
     * Use voucher for checkout
     *
     * @authenticated
     *
     * @response 200
     * {
     *     'message': 'Coupon applied successfully.'
     * }
     * @response status=422 scenario="Unprocessable Content"
     * {
     *   "message": "The coupon code field is required."
     * }
     * @response status=500 scenario="Server Error" {
     *     "message": "Server Error."
     * }
     * @response status=401 scenario="Unauthenticated" {
     *     "message": "Unauthenticated."
     * }
     */
    public function voucher(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);

        $coupon = Coupon::where('code', $request->coupon_code)->where('expire_date', '>=', date('Y-m-d'))->where('status', true)->first();
        if (! $coupon) {
            return response()->json(['message' => 'Voucher is not valid'], 302);
        }

        return response()->json([
            'message' => 'Coupon applied successfully',
            'coupon' => [
                'discount_type' => $coupon->discount_type,
                'discount' => $coupon->discount,
            ],
        ]);
    }
}
