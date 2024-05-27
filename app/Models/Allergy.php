<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'status'];

    /**
     * The foods that belong to the allergy.
     */
    public function foods()
    {
        return $this->belongsToMany(Food::class);
    }
}
