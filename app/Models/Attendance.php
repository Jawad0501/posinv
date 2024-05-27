<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'date', 'check_in', 'check_out', 'stay'];

    /**
     * Get the staff that owns the attendance.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
