<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * get active addons
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * The foods that belong to the Addon
     */
    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }

    /**
     * Get the order addon details item for the addon.
     */
    /**
     * Get all of the order Addon Details for the Addon
     */
    public function orderAddonDetails()
    {
        return $this->hasMany(OrderAddonDetails::class);
    }

    /**
     * Get all of the subAddons for the Addon
     */
    public function subAddons()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the addon that owns the Addon
     */
    public function addon()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}
