<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
 * get active status
 *
 * @param  mixed  $query
 * @return void
 */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get the income for the income category.
     */
    public function income()
    {
        return $this->hasMany(Income::class, 'category_id');
    }
}
