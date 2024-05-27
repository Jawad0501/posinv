<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    const TYPE_CREADIT = 'creadit';

    const TYPE_DEBIT = 'debit';

    protected $fillable = ['bank_id', 'withdraw_deposite_id', 'amount', 'type', 'decsription', 'date'];

    /**
     * Get the bank that owns the bank transition.
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
}
