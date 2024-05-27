<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'address' => 'object',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    // protected static function booted()
    // {
    //     static::created(function (Model $model) {
    //         $model->update([
    //             'invoice' => generate_invoice($model->id),
    //             'grand_total' => $grand_total,
    //             'token_no' => sprintf('%s%03s', '', DB::table('orders')->whereDate('created_at', date('Y-m-d'))->count()),
    //         ]);
    //     });
    // }

    /**
     * get by status
     */
    public function scopeByStatus($query, $value)
    {
        return $query->where('status', $value);
    }

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class)->where('role', 'User');
    }

    /**
     * Get the rider that owns the order.
     */
    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id')->where('role', 'Rider');
    }

    /**
     * Get the order details item for the order.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    /**
     * Get the tables for the order.
     */
    public function tables()
    {
        return $this->hasMany(OrderTable::class);
    }

    /**
     * Get the payment for the order.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function return()
    {
        return $this->hasMany(ProductReturn::class, 'orderdetail_id');
    }
}
