<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MealPeriod extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'time_slot' => 'object',
    ];

    /**
     * The foods that belong to the meal period.
     */
    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }
}
