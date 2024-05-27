<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Staff extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * is admin
     *
     * @return void
     */
    public function isAdmin()
    {
        return $this->role_id == 1 ? true : false;
    }

    /**
     * get active staff
     *
     * @param  mixed  $query
     * @return void
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * access permission
     */
    public function hasPermission($permission): bool
    {
        return $this->role->permissions->where('slug', $permission)->first() ? true : false;
    }

    /**
     * Get the role that owns the staff.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the stock adjustment for the staff.
     */
    public function stockAdjustments()
    {
        return $this->hasMany(StockAdjustment::class);
    }

    /**
     * Get the expense for the staff.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Get the waste for the staff.
     */
    public function wastes()
    {
        return $this->hasMany(Waste::class);
    }

    /**
     * Get the attendance for the staff.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
