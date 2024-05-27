<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'food_id',
        'purchase_id',
        'variant_id',
        'processing_time',
        'status',
        'price',
        'quantity',
        'vat',
        'total_price',
        'note',
    ];

    /**
     * Get the food that owns the order details.
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    /**
     * Get the variant that owns the order details.
     */
    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    /**
     * Get the purchase that owns the order details.
     */
    public function purchase()
    {
        return $this->belongsTo(PurchaseItem::class, 'purchase_id');
    }

    /**
     * Get the order that owns the order details.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the addons item for the order details.
     */
    public function addons()
    {
        return $this->hasMany(OrderAddonDetails::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'food_id');
    }
}
