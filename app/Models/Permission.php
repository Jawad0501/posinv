<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['module_id', 'name', 'slug'];

    /**
     * one to many relationship define by module model
     *
     * @return void
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * The roles that belong to the permission.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
