<?php

namespace App\Http\Controllers\Backend\Client;

use App\Enum\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke($status, $trx_id)
    {
        $giftcard = GiftCard::query()->with('order', 'order.orderDetails')->where('status', PaymentStatus::PENDING->value)->where('trx', $trx_id)->firstOrFail();

    }
}
