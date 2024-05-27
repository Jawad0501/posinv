<?php

namespace App\Enum;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case READY = 'ready';
    case SERVED = 'served';
    case SUCCESS = 'success';
    case CANCEL = 'cancel';
    case DUE = 'due';
    case PAID = 'paid';

}
