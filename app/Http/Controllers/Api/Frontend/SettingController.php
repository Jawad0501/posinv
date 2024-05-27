<?php

namespace App\Http\Controllers\Api\Frontend;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke()
    {
        return response()->json([
            'app_name' => setting('restaurant_name'),
            'email' => setting('email'),
            'phone' => setting('phone'),
            'white_logo' => setting(uploaded_file('white_logo')),
            'dark_logo' => setting(uploaded_file('dark_logo')),
            'favicon' => setting(uploaded_file('favicon')),
            'invoice_logo' => setting(uploaded_file('invoice_logo')),
            'service_charge' => setting('service_charge'),
            'delivery_charge' => setting('delivery_charge'),
            'reward_exchange_rate' => setting('reward_exchange_rate'),
            'currency_symbol' => setting('currency_symbol'),
            'currency_code' => setting('currency_code'),
            'currency_position' => setting('currency_position'),
        ]);
    }
}
