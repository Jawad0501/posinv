<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the table layout that owns the reservation.
     */
    public function tablelayout()
    {
        return $this->belongsTo(Tablelayout::class, 'table_id');
    }

    /**
     * Get the user that owns the Reservation
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
