<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * one to many relationship define by permission model
     */
    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }
}
