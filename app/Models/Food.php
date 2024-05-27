<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
     * get active food
     *
     * @param  mixed  $query
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * get online item visibility
     */
    public function scopeVisibility($query)
    {
        return $query->where('online_item_visibility', true);
    }

    /**
     * get sellable
     */
    public function scopeSellable($query)
    {
        return $query->where('sellable', true);
    }

    /**
     * find food by slug and status true
     *
     * @param  mixed  $slug
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->firstOrFail();
    }

    /**
     * Get the review item for the food.
     */
    public function reviews()
    {
        return $this->hasMany(OrderReview::class);
    }

    /**
     * Get the production units item for the food.
     */
    public function productionUnits()
    {
        return $this->hasMany(ProductionUnit::class);
    }

    /**
     * Get the ingredient item for the food.
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Get the order details item for the food.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    /**
     * The mealPeriods that belong to the food.
     */
    public function mealPeriods()
    {
        return $this->belongsToMany(MealPeriod::class);
    }

    /**
     * The categories that belong to the food.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * The addons that belong to the food.
     */
    public function addons()
    {
        return $this->belongsToMany(Addon::class);
    }

    /**
     * The allergies that belong to the food.
     */
    public function allergies()
    {
        return $this->belongsToMany(Allergy::class);
    }

    /**
     * Get the variants item for the food.
     */
    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    /**
     * Get all of the favorites for the Food
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
