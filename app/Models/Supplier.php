<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'reference', 'address', 'id_card_front', 'id_card_back', 'status', 'advance_amount'];

    /**
     * Get the purchase for the supplier.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
