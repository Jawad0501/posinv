<?php

namespace App\Enum;

enum OrderDetailsStatus: string
{
    case PENDING = 'pending';
    case COOKING = 'prepare';
    case READY = 'ready';
    case SERVED = 'served';
    case CANCEL = 'cancel';
}
