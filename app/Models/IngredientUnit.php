<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientUnit extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description', 'status'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::creating(fn (Model $model) => $model->slug = generate_invoice($model->name));
    }

    /**
     * get active chapter
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get the ingredient for the ingredient unit.
     */
    public function ingredients()
    {
        return $this->hasMany(Ingredient::class, 'unit_id');
    }
}
