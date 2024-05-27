<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['ingredient_id', 'qty_amount'];

    /**
     * Get the ingredient that owns the stock.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
