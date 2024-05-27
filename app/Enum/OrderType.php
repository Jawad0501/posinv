<?php

namespace App\Enum;

enum OrderType: string
{
    case DINEIN = 'Dine In';
    case TAKEWAY = 'Takeway';
    case DELIVERY = 'Delivery';
    case ONLINE = 'Online';
}
