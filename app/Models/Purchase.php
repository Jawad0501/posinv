<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    const PAYMENT_TYPE_CASH = 'cash payment';

    const PAYMENT_TYPE_BANK = 'bank payment';

    const PAYMENT_TYPE_DUE = 'due payment';

    protected $fillable = [
        'supplier_id',
        'bank_id',
        'reference_no',
        'total_amount',
        'shipping_charge',
        'discount_amount',
        'paid_amount',
        'status',
        'items',
        'date',
        'payment_type',
        'details',
        'settled_from_advance',
        'change_returned',
        'change_amount',
        'due_amount',
        'previous_due'
    ];

    protected $appends = [
        // 'due_amount',
        'grand_total',
    ];

    /**
     * Get the purchase grand total amount
     *
     * @return string
     */
    public function getGrandTotalAttribute()
    {
        return ($this->total_amount + $this->shipping_charge) - $this->discount_amount;
    }

    /**
     * Get the purchase due amount
     *
     * @return string
     */
    public function getDueAmountAttribute()
    {
        return $this->grand_total - $this->paid_amount;
    }

    /**
     * Get the supplier that owns the purchase.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the purchase item for the ingredient.
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    /**
     * Get the bank that owns the purchase.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
