<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'food_id'];

    /**
     * Get the user that owns the Favorite
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the food that owns the Favorite
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
