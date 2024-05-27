<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the staff that owns the expense.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the income category that owns the income.
     */
    public function category()
    {
        return $this->belongsTo(IncomeCategory::class, 'category_id');
    }
}
