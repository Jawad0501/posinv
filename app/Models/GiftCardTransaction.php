<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCardTransaction extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the user that owns the GiftCardTransaction
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
