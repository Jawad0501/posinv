<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'reference_no', 'date', 'note', 'added_by'];

    /**
     * Get the staff that owns the stock adjustment.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the stock adjustment item item for the stock adjustment.
     */
    public function items()
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }
}
