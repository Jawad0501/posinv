<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddonDetails extends Model
{
    protected $fillable = ['order_details_id', 'addon_id', 'price', 'quantity'];

    use HasFactory;

    /**
     * Get the order details that owns the order addon details.
     */
    public function orderDetails()
    {
        return $this->belongsTo(OrderDetails::class);
    }

    /**
     * Get the addon that owns the order addon details.
     */
    public function addon()
    {
        return $this->belongsTo(Addon::class);
    }
}
