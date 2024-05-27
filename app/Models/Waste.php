<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    use HasFactory;

    protected $fillable = ['staff_id', 'reference_no', 'date', 'note', 'added_by', 'items', 'total_loss'];

    /**
     * Get the staff that owns the waste.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
