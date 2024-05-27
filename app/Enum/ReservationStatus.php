<?php

namespace App\Enum;

enum ReservationStatus: string
{
    case HOLD = 'hold';
    case PENDING = 'pending';
    case CONFIRM = 'confirm';
    case CANCEL = 'cancel';
}
