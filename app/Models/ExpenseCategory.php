<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    /**
     * get active chapter
     *
     * @param  mixed  $query
     * @return void
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get the expense for the expense category.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }
}
