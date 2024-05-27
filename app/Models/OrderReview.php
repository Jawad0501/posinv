<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'food_id', 'rating', 'comment', 'status'];

    /**
     * get active review
     *
     * @param  mixed  $query
     * @return void
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get the user unit that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the food unit that owns the review.
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
