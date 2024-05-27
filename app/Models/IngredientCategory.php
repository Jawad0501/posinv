<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'status'];

    /**
     * get active category
     *
     * @param  mixed  $query
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get the ingredient for the ingredient category.
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'category_id');
    }
}
