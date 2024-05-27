<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product_return(){
        return $this->belongsTo(ProductReturn::class);
    }
}
