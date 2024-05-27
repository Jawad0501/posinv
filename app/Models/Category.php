<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'status', 'is_drinks'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(fn (Model $model) => $model->slug = generate_slug($model->name));
        static::updating(fn (Model $model) => $model->slug = generate_slug($model->name));
    }

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
     * The foods that belong to the category.
     */
    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }
}
