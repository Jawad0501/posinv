<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'category_id', 'date', 'amount', 'note', 'status'];

    /**
     * Get the staff that owns the expense.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the expense category that owns the expense.
     */
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
