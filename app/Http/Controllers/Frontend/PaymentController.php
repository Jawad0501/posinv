<?php

namespace App\Http\Controllers\Frontend;

use App\Enum\PaymentStatus;
use App\Http\Controllers\Controller;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use stdClass;
use Stripe;

class PaymentController extends Controller
{
    /**
     * stripe payment
     */
    public static function stripe($amount, $trx_id, $additionals = [])
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $send = new stdClass;
        try {
            $send->session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => strtolower(setting('currency_code')),
                        'unit_amount' => $amount * 100,
                        'product_data' => [
                            'name' => config('app.name'),
                            'description' => 'Payment  with Stripe',
                            // 'images' => [uploaded_file('assets/images/logoIcon/logo.png')],
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'cancel_url' => route('payment.callback', array_merge(['status' => PaymentStatus::CANCEL->value, 'trx_id' => $trx_id], $additionals)),
                'success_url' => route('payment.callback', array_merge(['status' => PaymentStatus::SUCCESS->value, 'trx_id' => $trx_id], $additionals)),
            ]);
        } catch (\Exception $e) {
            $send->error = true;
            $send->message = $e->getMessage();

            return $send;
        }

        return $send;
    }

    /**
     * paypal payment
     */
    public function paypal($amount, $trx_id, $additionals = [])
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        $response = $provider->createOrder([
            'intent' => 'CAPTURE',
            'application_context' => [
                'return_url' => route('payment.callback', array_merge(['status' => PaymentStatus::SUCCESS->value, 'trx_id' => $trx_id], $additionals)),
                'cancel_url' => route('payment.callback', array_merge(['status' => PaymentStatus::CANCEL->value, 'trx_id' => $trx_id], $additionals)),
            ],
            'purchase_units' => [
                0 => [
                    'amount' => [
                        'currency_code' => setting('currency_code'),
                        'value' => (string) $amount,
                    ],
                ],
            ],
        ]);

        return $response;
    }
}
