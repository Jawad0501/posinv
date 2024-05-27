<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'account_name',
        'account_number',
        'branch_name',
        'balance',
        'signature_image',
    ];

    /**
     * Get the transaction for the bank.
     */
    public function transactions()
    {
        return $this->hasMany(BankTransaction::class);
    }

    /**
     * Get the purchase for the bank.
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
