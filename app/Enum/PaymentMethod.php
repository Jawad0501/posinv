<?php

namespace App\Enum;

enum PaymentMethod: string
{
    case CASH = 'Cash';
    case CARD = 'Card';
    case STRIPE = 'Stripe';
    case PAYPAL = 'Paypal';
    case GIFTCARD = 'Gift Card';
}
