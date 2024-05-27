<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order_details(){
        return $this->belongsTo(Order::class, 'id');
    }

    public function items(){
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

}
