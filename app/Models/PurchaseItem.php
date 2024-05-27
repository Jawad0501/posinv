<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'ingredient_id', 'unit_price', 'quantity_amount', 'total', 'expire_date'];

    /**
     * Get the purchase that owns the purchase item.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the ingredient that owns the purchase item.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
