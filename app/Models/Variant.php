<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_id',
        'name',
        'price',
        'status',
        'sub_sku',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function (Model $model) {
            $model->sub_sku = sprintf('%s%05s', '', $model->id);
            $model->save();
        });
    }

    /**
     * Get the production units item for the variant.
     */
    public function productionUnits()
    {
        return $this->hasMany(ProductionUnit::class);
    }

    /**
     * Get the food for the variant.
     */
    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    /**
     * Get the orderDetails for the variant.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }
}
