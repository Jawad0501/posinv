<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tablelayout extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'number', 'capacity', 'available', 'image', 'status'];

    /**
     * get where id by table
     *
     * @param  mixed  $query
     * @return void
     */
    public function scopeById($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * get relationship order foods sum
     *
     * @param  mixed  $query
     */
    public function scopeReservationsSum($query)
    {
        return $query->withSum(
            [
                'reservations' => fn ($query) => $query->where('start_date', date('Y-m-d'))->whereNotIn('status', ['confirm', 'cancel']),
            ],
            'total_person'
        );
    }

    /**
     * get active table
     *
     * @param  mixed  $query
     * @return void
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Get the reservation for the table layout
     * .
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'table_id');
    }

    /**
     * Get the order item for the tablelayout.
     */
    public function orders()
    {
        return $this->hasMany(OrderTable::class, 'table_id');
    }
}
