<?php

namespace App\Enum;

enum OrderDeliveryType: string
{
    case PICKUP = 'pickup';
    case DELIVERY = 'delivery';
}
