<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'table_id', 'total_person'];

    /**
     * Get the order that owns the order table.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the table that owns the order table.
     */
    public function table()
    {
        return $this->belongsTo(Tablelayout::class, 'table_id');
    }
}
